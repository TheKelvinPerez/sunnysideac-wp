#!/bin/bash

# Define colors for better output
GREEN='\033[0;32m'
RED='\033[0;31m'
BLUE='\033[0;34m'
YELLOW='\033[0;33m'
NC='\033[0m' # No Color

# API Base URL
BASE_URL="http://seobot.local/seobot/v1"

# Hardcoded API key for testing
API_KEY="test-api-key-1234567890"

# Basic Auth credentials for testing
BASIC_AUTH_USER="$API_KEY"
BASIC_AUTH_PASS="password"

# Test image file - use existing iab-cropped.png
IMAGE_FILE="iab-cropped.png"

# Check if the image file exists
if [ ! -f "$IMAGE_FILE" ]; then
    echo -e "${RED}ERROR: Test image file $IMAGE_FILE not found!${NC}"
    echo -e "${YELLOW}Please make sure the iab-cropped.png file exists in this directory.${NC}"
    exit 1
else
    echo -e "${GREEN}Found test image: $IMAGE_FILE${NC}"
fi

# Function to test an endpoint
test_endpoint() {
    local method="$1"
    local endpoint="$2"
    local data="$3"
    local description="$4"
    local content_type="${5:-application/json}"
    local is_multipart="${6:-false}"

    echo -e "\n${BLUE}Testing: $description${NC}"
    echo -e "${YELLOW}$method $endpoint${NC}"

    # Test with Basic Auth (API key as username)
    echo -e "\n${YELLOW}Authentication: Basic Auth with API key as username${NC}"

    local curl_cmd="curl -s -X $method --user \"$BASIC_AUTH_USER:$BASIC_AUTH_PASS\" \"$BASE_URL$endpoint\""

    if [ "$is_multipart" = "true" ]; then
        # For multipart/form-data (file uploads)
        local IFS='&'
        local data_array=($data)
        for item in "${data_array[@]}"; do
            local key=$(echo $item | cut -d= -f1)
            local value=$(echo $item | cut -d= -f2-)

            if [[ $key == "file" ]]; then
                curl_cmd+=" -F \"$key=@$value\""
            else
                curl_cmd+=" -F \"$key=$value\""
            fi
        done
    elif [ -n "$data" ]; then
        # For JSON and other content types with data
        curl_cmd+=" -H \"Content-Type: $content_type\" -d '$data'"
    fi

    # Execute the curl command and capture the response
    local RESPONSE
    if [ "$is_multipart" = "true" ]; then
        # For multipart/form-data, we need to build the curl command differently
        if [ "$method" = "POST" ] && [[ "$endpoint" == "/media" ]]; then
            UPLOAD_RESPONSE=$(curl -s -X $method \
                --user "$BASIC_AUTH_USER:$BASIC_AUTH_PASS" \
                "$BASE_URL$endpoint" \
                -F "file=@$IMAGE_FILE" \
                -F "title=Test Image")

            # Show the beginning of the response for debugging
            echo -e "\n${YELLOW}Response preview:${NC}"
            echo "${UPLOAD_RESPONSE:0:300}"

            # Extract media ID for debugging
            if [[ $UPLOAD_RESPONSE == *"\"id\":"* ]]; then
                MEDIA_ID=$(echo $UPLOAD_RESPONSE | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
                echo -e "${GREEN}✓ Success with Basic Auth - Media ID: $MEDIA_ID${NC}"
                return 0
            elif [[ $UPLOAD_RESPONSE == *"error"* || $UPLOAD_RESPONSE == *"code"* ]]; then
                echo -e "${RED}✗ Failed with Basic Auth${NC}"
                return 1
            else
                echo -e "${YELLOW}⚠ Unknown response format${NC}"
                return 1
            fi
        fi
    else
        RESPONSE=$(eval $curl_cmd)
    fi

    # For GET /media endpoint, special handling
    if [ "$method" = "GET" ] && [[ "$endpoint" == "/media" ]]; then
        echo -e "\n${YELLOW}Response preview:${NC}"
        echo "${RESPONSE:0:300}"

        if [[ $RESPONSE == "["* ]]; then
            # Count the number of media items by counting "id" occurrences
            ITEM_COUNT=$(echo $RESPONSE | grep -o '"id"' | wc -l | tr -d ' ')
            echo -e "${GREEN}✓ Success with Basic Auth - Found $ITEM_COUNT media items${NC}"
            return 0
        elif [[ $RESPONSE == *"error"* || $RESPONSE == *"code"* ]]; then
            echo -e "${RED}✗ Failed with Basic Auth${NC}"
            return 1
        else
            echo -e "${YELLOW}⚠ Unknown response format${NC}"
            return 1
        fi
    fi

    # For standard JSON responses, check if it's a valid JSON and no error
    if [[ "$endpoint" == "/post" && "$method" == "POST" ]] || [[ "$endpoint" == "/post/"* && "$method" == "PUT" ]]; then
        echo -e "\n${YELLOW}Full Response:${NC}"
        echo "$RESPONSE" | python -m json.tool 2>/dev/null || echo "$RESPONSE"

        if [[ $RESPONSE == *"\"id\":"* ]]; then
            POST_ID=$(echo $RESPONSE | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
            echo -e "${GREEN}✓ Success with Basic Auth - Post ID: $POST_ID${NC}"
            return 0
        elif [[ $RESPONSE == *"error"* || $RESPONSE == *"code"* ]]; then
            echo -e "${RED}✗ Failed with Basic Auth${NC}"
            return 1
        else
            echo -e "${YELLOW}⚠ Unknown response format${NC}"
            return 1
        fi
    else
        # For other endpoints, standard JSON response handling
        echo -e "\n${YELLOW}Response:${NC}"
        echo "$RESPONSE" | python -m json.tool 2>/dev/null || echo "$RESPONSE"

        if [[ $RESPONSE == *"error"* || $RESPONSE == *"code"* ]]; then
            echo -e "${RED}✗ Failed with Basic Auth${NC}"
            return 1
        else
            echo -e "${GREEN}✓ Success with Basic Auth${NC}"
            return 0
        fi
    fi
}

echo -e "${BLUE}=====================================${NC}"
echo -e "${BLUE}   SEObot API Comprehensive Test    ${NC}"
echo -e "${BLUE}=====================================${NC}"
echo -e "${YELLOW}This test verifies all SEObot API endpoints using Basic Auth authentication.${NC}"
echo -e "${YELLOW}Note: The API works even when WordPress REST API is disabled.${NC}"

# Test each endpoint
test_endpoint "GET" "/categories" "" "Get all categories"
test_endpoint "GET" "/post" "" "Get all posts"
test_endpoint "GET" "/post/hello-world" "" "Get post by slug"
test_endpoint "POST" "/post" '{"title":"Test Post via API","content":"This is a test post created via API","status":"publish","categories":[8],"seo_data":{"title":"SEO Optimized Title","description":"This is a meta description for SEO","keywords":"test seo api","og_title":"Open Graph Title","og_description":"Open Graph Description","og_image":"http://example.com/image.jpg"}}' "Create new post with SEO metadata"
test_endpoint "POST" "/media" "file=$IMAGE_FILE&title=Test Image" "Upload media" "multipart/form-data" true
test_endpoint "GET" "/media" "" "Get all media"

# Get the ID of the last created post for updating
LAST_POST_ID=$(curl -s -X GET --user "$BASIC_AUTH_USER:$BASIC_AUTH_PASS" "$BASE_URL/post" | grep -o '"id":[0-9]*' | tail -1 | cut -d':' -f2)

if [ ! -z "$LAST_POST_ID" ]; then
    test_endpoint "PUT" "/post/$LAST_POST_ID" '{"title":"Updated Post Title","content":"Updated content with SEO metadata","status":"publish","seo_data":{"title":"Updated SEO Title","description":"Updated meta description","keywords":"updated seo keywords","og_title":"Updated OG Title","og_description":"Updated OG Description","og_image":"http://example.com/updated-image.jpg"}}' "Update post with SEO metadata"
fi

echo -e "\n${GREEN}Comprehensive API Testing Complete!${NC}"
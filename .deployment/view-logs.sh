#!/bin/bash

##############################################################################
# Deployment Logs Viewer
#
# View and manage deployment logs on production server
#
# Usage:
#   ssh root@sunnysideac "bash -s" < .deployment/view-logs.sh [command]
#
# Or on server directly:
#   bash .deployment/view-logs.sh [command]
#
# Commands:
#   latest        Show the most recent deployment log (default)
#   list          List all deployment logs
#   tail          Follow the latest deployment log in real-time
#   clean         Manually clean logs older than 7 days
#   stats         Show deployment statistics
##############################################################################

LOG_DIR="/var/www/sunnyside247ac_com/logs/deployments"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

show_latest() {
    echo -e "${GREEN}=== Latest Deployment Log ===${NC}"
    local latest_log=$(ls -t "$LOG_DIR"/deploy-*.log 2>/dev/null | head -1)

    if [ -z "$latest_log" ]; then
        echo "No deployment logs found"
        exit 0
    fi

    echo -e "${BLUE}File: $(basename "$latest_log")${NC}"
    echo ""
    cat "$latest_log"
}

list_logs() {
    echo -e "${GREEN}=== Deployment Logs (Last 7 Days) ===${NC}"
    echo ""

    if [ ! -d "$LOG_DIR" ] || [ -z "$(ls -A "$LOG_DIR" 2>/dev/null)" ]; then
        echo "No deployment logs found"
        exit 0
    fi

    cd "$LOG_DIR" || exit 1

    printf "%-25s %10s %s\n" "FILENAME" "SIZE" "DATE"
    printf "%-25s %10s %s\n" "--------" "----" "----"

    for log in $(ls -t deploy-*.log 2>/dev/null); do
        size=$(du -h "$log" | cut -f1)
        date=$(stat -c '%y' "$log" 2>/dev/null || stat -f '%Sm' "$log" 2>/dev/null)
        printf "%-25s %10s %s\n" "$log" "$size" "$date"
    done
}

tail_log() {
    echo -e "${GREEN}=== Following Latest Deployment Log (Ctrl+C to exit) ===${NC}"
    local latest_log=$(ls -t "$LOG_DIR"/deploy-*.log 2>/dev/null | head -1)

    if [ -z "$latest_log" ]; then
        echo "No deployment logs found"
        exit 0
    fi

    echo -e "${BLUE}File: $(basename "$latest_log")${NC}"
    echo ""
    tail -f "$latest_log"
}

clean_logs() {
    echo -e "${GREEN}=== Cleaning Old Deployment Logs ===${NC}"

    local count_before=$(find "$LOG_DIR" -name "deploy-*.log" -type f 2>/dev/null | wc -l | tr -d ' ')
    echo "Logs before cleanup: $count_before"

    find "$LOG_DIR" -name "deploy-*.log" -type f -mtime +7 -delete 2>/dev/null

    local count_after=$(find "$LOG_DIR" -name "deploy-*.log" -type f 2>/dev/null | wc -l | tr -d ' ')
    local deleted=$((count_before - count_after))

    echo "Logs after cleanup: $count_after"
    echo "Deleted: $deleted logs"
}

show_stats() {
    echo -e "${GREEN}=== Deployment Statistics ===${NC}"
    echo ""

    if [ ! -d "$LOG_DIR" ] || [ -z "$(ls -A "$LOG_DIR" 2>/dev/null)" ]; then
        echo "No deployment logs found"
        exit 0
    fi

    local total_logs=$(find "$LOG_DIR" -name "deploy-*.log" -type f 2>/dev/null | wc -l | tr -d ' ')
    local total_size=$(du -sh "$LOG_DIR" 2>/dev/null | cut -f1)
    local latest_log=$(ls -t "$LOG_DIR"/deploy-*.log 2>/dev/null | head -1)
    local latest_date=$(stat -c '%y' "$latest_log" 2>/dev/null || stat -f '%Sm' "$latest_log" 2>/dev/null)

    echo "Total deployments logged: $total_logs"
    echo "Total log size: $total_size"
    echo "Latest deployment: $latest_date"
    echo ""

    # Count successful vs failed deployments (case-insensitive)
    local successful=$(grep -li "deployment completed successfully" "$LOG_DIR"/deploy-*.log 2>/dev/null | wc -l | tr -d ' ')
    local failed=$((total_logs - successful))

    echo "Successful deployments: $successful"
    echo "Failed deployments: $failed"
}

# Main
case "${1:-latest}" in
    latest)
        show_latest
        ;;
    list)
        list_logs
        ;;
    tail)
        tail_log
        ;;
    clean)
        clean_logs
        ;;
    stats)
        show_stats
        ;;
    *)
        echo "Usage: $0 {latest|list|tail|clean|stats}"
        echo ""
        echo "Commands:"
        echo "  latest  - Show the most recent deployment log (default)"
        echo "  list    - List all deployment logs"
        echo "  tail    - Follow the latest deployment log in real-time"
        echo "  clean   - Manually clean logs older than 7 days"
        echo "  stats   - Show deployment statistics"
        exit 1
        ;;
esac

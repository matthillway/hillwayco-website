#!/bin/bash
# Hillway Website Deployment Script
# Syncs local changes to one.com server via SFTP
#
# Usage: ./deploy.sh
#
# Prerequisites:
# - lftp installed (brew install lftp)
# - SSH host key added to known_hosts

# Configuration
SFTP_HOST="ssh.c56g03m6y.service.one"
SFTP_USER="c56g03m6y_ssh"
SFTP_PORT="22"
REMOTE_PATH="webroots/by-route/www.hillwayco.uk_/"
LOCAL_PATH="$(dirname "$0")"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}🚀 Hillway Website Deployment${NC}"
echo "================================"

# Check if lftp is installed
if ! command -v lftp &> /dev/null; then
    echo -e "${RED}Error: lftp is not installed. Run: brew install lftp${NC}"
    exit 1
fi

# Prompt for password (don't store in script)
echo -n "Enter SFTP password: "
read -s SFTP_PASS
echo ""

# Exclude files that shouldn't be uploaded
EXCLUDE_OPTS="--exclude .git/ --exclude .gitignore --exclude deploy.sh --exclude README.md --exclude .DS_Store --exclude gdpr-consent-log.txt"

echo -e "${YELLOW}Uploading changes to one.com...${NC}"

# Mirror local to remote (upload only changes)
lftp -e "
set sftp:auto-confirm yes
open -u $SFTP_USER,$SFTP_PASS sftp://$SFTP_HOST:$SFTP_PORT
mirror --reverse --verbose --only-newer $EXCLUDE_OPTS $LOCAL_PATH $REMOTE_PATH
quit
"

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✅ Deployment complete!${NC}"
    echo -e "Visit: https://www.hillwayco.uk"
else
    echo -e "${RED}❌ Deployment failed${NC}"
    exit 1
fi

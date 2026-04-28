#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

DEPLOY_HOST="${DEPLOY_HOST:-109.172.46.96}"
DEPLOY_USER="${DEPLOY_USER:-root}"
DEPLOY_PORT="${DEPLOY_PORT:-22}"
DEPLOY_PATH="${DEPLOY_PATH:-/opt/apps/carsdevdenis/theme/cars/}"

if command -v sshpass >/dev/null 2>&1 && [ -n "${DEPLOY_PASSWORD:-}" ]; then
  export SSHPASS="${DEPLOY_PASSWORD}"
  SSH_CMD="sshpass -e ssh -p ${DEPLOY_PORT} -o StrictHostKeyChecking=accept-new"
else
  SSH_CMD="ssh -p ${DEPLOY_PORT} -o StrictHostKeyChecking=accept-new"
fi

echo "Deploying theme to ${DEPLOY_USER}@${DEPLOY_HOST}:${DEPLOY_PATH}"

rsync -az --delete \
  --exclude '.git/' \
  --exclude '.github/' \
  --exclude '.DS_Store' \
  -e "${SSH_CMD}" \
  "${ROOT_DIR}/" "${DEPLOY_USER}@${DEPLOY_HOST}:${DEPLOY_PATH}"

echo "Done."


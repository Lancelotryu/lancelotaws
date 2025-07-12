#!/usr/bin/env bash
#
# deploy.sh — one-click Git sync for Git Bash
# 1) auto-starts ssh-agent if needed
# 2) loads your private key
# 3) commits + pushes if there is anything to sync

# --- config -------------------------------------------------------------
KEY="$HOME/.ssh/id_ed25519"      # ← adapte si ta clé privée se nomme autrement

# --- start agent + add key (idempotent) ---------------------------------
pgrep -u "$USER" ssh-agent >/dev/null || eval "$(ssh-agent -s)" >/dev/null
ssh-add "$KEY" 2>/dev/null

# --- go to repo root (folder where this script lives) -------------------
cd "$(dirname "$0")" || exit 1

# --- nothing to commit ? ------------------------------------------------
git diff --quiet && { echo "Nothing to sync 🚀"; exit 0; }

# --- build timestamped commit message -----------------------------------
msg="Sync $(date +%Y-%m-%dT%H-%M-%S)"

# --- stage, commit, push -------------------------------------------------
git add -A
git commit -m "$msg"
git push

echo "Synchronization completed ✔"

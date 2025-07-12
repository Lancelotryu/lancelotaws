#!/usr/bin/env bash
set -euo pipefail
KEY="$HOME/.ssh/clef"

cd "$(dirname "$0")"

# ── start agent & add key (inchangé) ─────────────────────────────────────
if ! ssh-add -l >/dev/null 2>&1; then
    eval "$(ssh-agent -s)" >/dev/null
    ssh-add "$KEY" >/dev/null
fi

# ── 1) stage EVERYTHING (tracked + untracked) ────────────────────────────
git add -A

# ── 2) encore rien à committer ? ─────────────────────────────────────────
git diff --cached --quiet && { echo "Nothing to sync 🚀"; exit 0; }

# ── 3) commit + push ─────────────────────────────────────────────────────
msg="Sync $(date +%Y-%m-%dT%H-%M-%S)"
git commit -m "$msg"
git push
echo "Synchronization completed ✔"

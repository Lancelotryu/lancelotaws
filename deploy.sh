#!/usr/bin/env bash
#
# deploy.sh — one-click Git sync for Git Bash
# • Starts ssh-agent only if necessary
# • Loads the private key
# • Commits + pushes if something changed

set -euo pipefail

KEY="$HOME/.ssh/clef"         # ← adapte si besoin

# ---- ensure we’re at repo root (folder of this script) ------------------
cd "$(dirname "$0")"

# ---- start agent / add key if no key loaded ----------------------------
if ! ssh-add -l >/dev/null 2>&1; then
    eval "$(ssh-agent -s)" >/dev/null
    ssh-add "$KEY"          # demande le passphrase une seule fois
fi

# ---- nothing to commit ? ------------------------------------------------
git diff --quiet && { echo "Nothing to sync"; exit 0; }

# ---- timestamped commit message ----------------------------------------
msg="Sync $(date +%Y-%m-%dT%H-%M-%S)"

# ---- stage, commit, push ------------------------------------------------
git add -A
git commit -m "$msg"
git push                          # upstream déjà configuré
echo "Synchronization completed"

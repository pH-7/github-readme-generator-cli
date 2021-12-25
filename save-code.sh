#!/bin/bash

# Save a git project to a specific repo (e.g. github, bitbucket, ...)
function save-project-to-repo() {
    git remote rm origin
    git remote add origin $1
    git push
}

declare readonly gitRemotes=(
    git@gitlab.com:pH-7/github-readme-generator-cli.git
    git@bitbucket.org:pH_7/random-passcode-password.git
    git@github.com:pH-7/php-github-readme-generator-seo-friendly.git
)
for remote in "${gitRemotes[@]}"
do
    save-project-to-repo $remote
done

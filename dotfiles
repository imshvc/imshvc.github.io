#!/usr/bin/env bash

# authors:    Nurudin Imsirovic <imshvc>
# file:       bootstrap-remote.sh
# desc:       bootstrap my dotfiles remotely
# created:    2025-01-24 01:46 AM
# updated:    2025-01-24 05:58 AM
# repository: https://github.com/imshvc/dotfiles

# one-liner: curl -sSL https://imshvc.github.io/dotfiles | /bin/bash -i 2>/dev/null

# root user: 2 methods
# -- method 1: source files from other user
#      run the command as a regular user:
#        sudo sed "s|\$HOME|/home/$USER|g" ~/.bashrc | sudo tee -a /root/.bashrc >&/dev/null
#
#      sourcing files from other user's home folder is not recommended
#      for security reasons, but I structured my files so that they play
#      nicely alongside two users (the regular user, and root user).
#
#      but then again - you should be bootstrapping into the root folder
#      separately, see method 2:
#
# -- method 2: bootstrap root user
#      run the one-liner like so:
#        curl -sSL https://imshvc.github.io/dotfiles | ALLOW_ROOT=1 /bin/bash -i 2>/dev/null
#
#      this will bootstrap even if the user is root.
#      files will have to be managed separately for each user,
#      but this method is considered more secure as two
#      configurations don't overlap.

# clear console screen
clear

# bootstrap: not allowed for root user
# unless: ALLOW_ROOT is set
if [ "$ALLOW_ROOT" == "" ]; then
  if [ "$USER" == "root" ]; then
    echo "fail: root user not allowed."
    exit 1
  fi

  if [ "$HOME" == "/root" ]; then
    echo"fail: root user not allowed."
    exit 1
  fi
fi

pushd "$HOME" >&/dev/null

# fail: already bootstrapped
# delete file to bootstrap again
if [ -f "$HOME/.dotfiles_bootstrapped" ]; then
  echo -e "fail:\n"
  echo -e "  bootstrapped already.\n"
  echo -e "  to bootstrap again run: 'rm -f ~/.dotfiles_bootstrapped'\n"
  echo -e "  then retry the bootstrap command."
  popd
  exit 1
fi

echo "info: bootstrap in progress"

# set: default repo
repo_url="https://raw.githubusercontent.com/imshvc/dotfiles/refs/heads/main"

# warn: files will be overwritten without prompt
mkdir ".local" >&/dev/null
mkdir ".local/bin" >&/dev/null

curl -sSL "$repo_url/.bash_aliases" > .bash_aliases 2>/dev/null
curl -sSL "$repo_url/.bash_config" > .bash_config 2>/dev/null
curl -sSL "$repo_url/.bash_exports" > .bash_exports 2>/dev/null
curl -sSL "$repo_url/.bash_functions" > .bash_functions 2>/dev/null
# curl -sSL "$repo_url/.bash_history" > .bash_history 2>/dev/null
curl -sSL "$repo_url/.bash_logout" > .bash_logout 2>/dev/null
curl -sSL "$repo_url/.bashrc" > .bashrc 2>/dev/null
curl -sSL "$repo_url/.gitconfig" > .gitconfig 2>/dev/null
curl -sSL "$repo_url/.nanorc" > .nanorc 2>/dev/null
curl -sSL "$repo_url/.profile" > .profile 2>/dev/null
curl -sSL "$repo_url/.wgetrc" > .wgetrc 2>/dev/null

touch .dotfiles_bootstrapped >&/dev/null

source .bashrc

echo "pass: dotfiles bootstrapped"
echo "note: re-open your terminal"

popd >&/dev/null
exit 0

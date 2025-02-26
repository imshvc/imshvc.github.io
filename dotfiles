#!/usr/bin/env bash

# authors:
#   Nurudin Imsirovic <imshvc>
#
# file:
#   bootstrap.sh
#
# summary:
#   Downloads the repository as an archive and once extracted it executes apply.sh
#   which deploys files from the dotfiles directory to the home directory of the
#   current user.  Same applies to other users on the system, the root user would
#   need to run apply.sh by hand to apply dotfiles to the root user.
#
# created:
#   2025-02-07 08:55 PM
#
# updated:
#   2025-02-26 11:06 AM
#
# repository:
#   https://github.com/imshvc/dotfiles

# set: dotfiles file name and download link
dotfiles_file="dotfiles-main.tar.gz"
dotfiles_link="https://codeload.github.com/imshvc/dotfiles/tar.gz/refs/heads/main"

# set: state
has_curl=0
has_wget=0
has_tar=0
has_unzip=0
downloader=0
home_path=0
is_msys2=0

# detect: cygwin
# fail: not a supported platform
if [[ "$(uname -s)" =~ ^CYGWIN_NT.* ]]; then
  echo "fail: cygwin is not a supported platform"
  exit 1
fi

# detect: msys2
if [[ "$(uname -s)" =~ ^MSYS_NT.* ]]; then
  is_msys2=1

  # fix: msys2: user not set
  if [ "$USER" = "" ]; then
    USER=$USERNAME
  fi
fi

# do: check for curl
if command -v curl 2>&1 >/dev/null; then
  # pass: curl exists
  has_curl=1
fi

# do: check for wget
if command -v wget 2>&1 >/dev/null; then
  # pass: wget exists
  has_wget=1
fi

# fail: no curl nor wget
if [ $has_curl = 0 ] && [ $has_wget = 0 ]; then
  echo "fail: no curl and wget - cannot download archive"
  exit 2
fi

# set: curl as downloader
if [ $has_curl = 1 ]; then
  downloader=curl
fi

# set: wget as downloader (no curl)
if [ $downloader = 0 ] && [ $has_wget = 1 ]; then
  downloader=wget
fi

# do: check for tar
if command -v tar 2>&1 >/dev/null; then
  # pass: tar exists
  has_tar=1
fi

# fail: no tar
if [ $has_tar = 0 ]; then
  echo "fail: no tar - cannot extract archive"
  exit 3
fi

# do: check for unzip
if command -v unzip 2>&1 >/dev/null; then
  # pass: unzip exists
  has_unzip=1
fi

# fail: no unzip
if [ $has_unzip = 0 ]; then
  echo "fail: no unzip - cannot extract archive"
  exit 3
fi

# set: absolute home path
if [ $EUID = 0 ]; then
  home_path="/root"
else
  # fix: msys2: inject windows path
  if [ $is_msys2 = 1 ]; then
    home_path="/c/Users/$USER"
  else
    home_path="/home/$USER"
  fi
fi

# fail: invalid home path
if [ ! -d "$home_path" ]; then
  # fail: fake root user
  if [ $EUID != 0 ] && [ "${USER,,}" = "root" ]; then
    echo "fail: fake root user - try with sudo"
    exit 4
  fi

  # fail: invalid user
  echo "fail: invalid user \"$USER\""
  exit 5
fi

# fail: dotfiles already exists
if [ -d "$home_path/dotfiles" ]; then
  echo "fail: destination \"$home_path/dotfiles\" already exists - delete and try again"
  exit 6
fi

# do: create dotfiles directory
mkdir -p "$home_path/dotfiles" 2>&1 >/dev/null

# fail: dotfiles directory
if [ ! -d "$home_path/dotfiles" ]; then
  echo "fail: destination \"$home_path/dotfiles\" could not be created - no further info"
  exit 7
fi

pushd "$home_path/dotfiles" 2>&1 >/dev/null

# do: download archive
if [ $downloader = curl ]; then
  # pass: curl
  curl --silent --insecure -o "$dotfiles_file" "$dotfiles_link"
elif [ $downloader = wget ]; then
  # pass: wget
  wget -nc -q -O "$dotfiles_file" "$dotfiles_link"
else
  # fail: unknown downloader
  echo "fail: unknown downloader the script does not support"
  exit 8
fi

# fail: download failed
if [ ! -f "$dotfiles_file" ]; then
  echo "fail: archive download failed: $dotfiles_link"
  exit 9
fi

# do: extract archive (stripping dotfiles-main)
tar --strip-components=1 -xzvf "$dotfiles_file" 2>&1 >/dev/null

# do: check for apply.sh
if [ -f "apply.sh" ]; then
  # pass: found
  chmod +x "apply.sh"
  ./apply.sh "$home_path" 2>&1 >/dev/null
else
  # fail: not found
  echo "fail: apply.sh not found"
fi

# do: make bootstrap.sh executable
# because by default it is not
if [ -f "bootstrap.sh" ]; then
  chmod +x "bootstrap.sh"
fi

popd 2>&1 >/dev/null

# pass: successful bootstrap
echo "imshvc/dotfiles... done"
exit 0

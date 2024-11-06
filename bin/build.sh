#!/usr/bin/env bash

# Exit if any command fails.
set -e

# Change to the expected directory.
cd "$(dirname "$0")"
cd ..
DIR=$(pwd)
BUILD_DIR="$DIR/build/dokan-customers"

# Enable nicer messaging for build status.
BLUE_BOLD='\033[1;34m'
GREEN_BOLD='\033[1;32m'
RED_BOLD='\033[1;31m'
YELLOW_BOLD='\033[1;33m'
COLOR_RESET='\033[0m'

error() {
    echo -e "\n${RED_BOLD}$1${COLOR_RESET}\n"
}
status() {
    echo -e "\n${BLUE_BOLD}$1${COLOR_RESET}\n"
}
success() {
    echo -e "\n${GREEN_BOLD}$1${COLOR_RESET}\n"
}
warning() {
    echo -e "\n${YELLOW_BOLD}$1${COLOR_RESET}\n"
}

status "💃 Time to build the Texty Pro ZIP file 🕺"

# remove the build directory if exists and create one
rm -rf "$DIR/build"
mkdir -p "$BUILD_DIR"

# Run the build.
# status "Installing dependencies... 📦"
# npm install

status "Generating build... 👷‍♀️"

# Copy all files
status "Copying files... ✌️"
FILES=(dokan-customers.php readme.txt dist includes templates assets languages composer.json composer.lock)

for file in ${FILES[@]}; do
    if [ -f "$file" ] || [ -d "$file" ]; then
        cp -R $file $BUILD_DIR
    fi
done

# Install composer dependencies
status "Installing dependencies... 📦"
cd $BUILD_DIR
composer install --optimize-autoloader --no-dev -q

# Remove composer files
rm composer.json composer.lock

# go one up, to the build dir
status "Creating archive... 🎁"
cd ..
zip -r -q dokan-customers.zip dokan-customers

# Unzip folder for testing purposes
status "Unzipping folder... 📂"
unzip -q dokan-customers.zip -d dokan-customers-unzipped

# remove the source directory
rm -rf dokan-customers

success "Done. You've built Vendor Dashboard Booster! 🎉 "
echo -e "\n${BLUE_BOLD}File Path${COLOR_RESET}: ${YELLOW_BOLD}$(pwd)/dokan-customers.zip${COLOR_RESET} \n"

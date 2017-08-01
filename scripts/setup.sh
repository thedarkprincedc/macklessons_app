#!/bin/bash
npm install
pushd resources
npm install
popd 
cd crawler 
composer install
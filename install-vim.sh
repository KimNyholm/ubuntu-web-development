#!/bin/bash

#configure vim
sudo tee ~/.vimrc <<CONFIG
set number
match ErrorMsg '\s\+$\|\t'
set expandtab
set tabstop=2
CONFIG

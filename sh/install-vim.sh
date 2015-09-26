#!/bin/bash

#configure vim
tee ~/.vimrc <<CONFIG
set number
match ErrorMsg '\s\+$\|\t'
set expandtab
set tabstop=2
set shiftwidth=2
CONFIG

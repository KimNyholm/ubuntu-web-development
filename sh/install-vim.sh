#!/bin/bash


#download vim
sudo apt-get --yes install vim
#configure vim
tee ~/.vimrc <<CONFIG
set t_Co=256
set number
match ErrorMsg '\s\+$\|\t'
set expandtab
set tabstop=2
set shiftwidth=2
set fileformats=unix
CONFIG

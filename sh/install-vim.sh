#!/bin/bash

#configure vim
tee ~/.vimrc <<CONFIG
set t_Co=256
highlight Normal ctermfg=black ctermbg=white
set number
match ErrorMsg '\s\+$\|\t'
set expandtab
set tabstop=2
set shiftwidth=2
set fileformats=unix
CONFIG

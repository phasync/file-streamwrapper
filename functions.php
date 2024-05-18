<?php
namespace phasync;

use phasync\FileStreamWrapper\FileStreamWrapper;

\phasync::onEnter(FileStreamWrapper::enable(...));
\phasync::onExit(FileStreamWrapper::disable(...));
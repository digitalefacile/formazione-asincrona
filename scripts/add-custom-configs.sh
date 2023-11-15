#!/usr/bin/env bash

[ -f /opt/bitnami/moodle/config-custom.php ] && sed -i '/require_once(.*setup\.php/e sed "1s/<?php/\\n/" /opt/bitnami/moodle/config-custom.php' /opt/bitnami/moodle/config.php


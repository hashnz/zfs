# Zfs

A PHP library for working with [ZFS](https://pthree.org/2012/04/17/install-zfs-on-debian-gnulinux/).

## Installation

Add to your composer.json

    "require": {
        "hashnz/zfs": "1.0.x-dev"
    },

Install with composer:

    $ composer update hashnz/zfs

## Usage

```php
use Hashnz\Zfs;
use Symfony\Component\Process\ProcessBuilder;

$zfs = new Zfs(new ProcessBuilder());

// create a filesystem in your pool
$zfs->createFilesystem('zpool/foo');

// get filesystem info
$zfs->getFilesystem('zpool/foo');

// get all filesystems
$zfs->getFilesystems();

// destroy filesystem
$zfs->destroyFilesystem('zpool/foo');

// create snapshot
$zfs->createSnapshot('zpool/foo', 'snap1');

// get snapshots
$zfs->getSnapshots('zpool/foo');

// clone a snapshot
$zfs->createClone('zpool/foo@snap1', 'zpool/bar');
```

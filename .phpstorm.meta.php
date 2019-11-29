<?php

declare(strict_types=1);

namespace PHPSTORM_META {

    use Moka\Moka;

    override(
        Moka::phpunit(0),
        map([
            '' => '@',
        ])
    );

    override(
        Moka::prophecy(0),
        map([
            '' => '@',
        ])
    );

    override(
        Moka::mockery(0),
        map([
            '' => '@',
        ])
    );

    override(
        Moka::phake(0),
        map([
            '' => '@',
        ])
    );

    override(
        \Moka\Plugin\PHPUnit\moka(0),
        map([
            '' => '@',
        ])
    );

    override(
        \Moka\Plugin\Prophecy\moka(0),
        map([
            '' => '@',
        ])
    );

    override(
        \Moka\Plugin\Mockery\moka(0),
        map([
            '' => '@',
        ])
    );

    override(
        \Moka\Plugin\Phake\moka(0),
        map([
            '' => '@',
        ])
    );
}

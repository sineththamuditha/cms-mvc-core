<?php

namespace app\core\components\layout;

class infoDiv
{
    public function __construct($gridCount)
    {
        $class = "grid-" . implode("-", $gridCount);
        echo "<div class='info-container $class'>";
    }

    public function statDivStart(): void
    {
        echo "<div class='stat-box'>";
    }

    public function chartDivStart(): void
    {
        echo "<div class='chart-box'>";
    }

    public function chartCanvas($id): void
    {
        echo "<canvas id='$id'></canvas>";
    }

    public function statDivEnd(): void
    {
        echo "</div>";
    }

    public function chartDivEnd(): void
    {
        echo "</div>";
    }

    public function end(): void
    {
        echo "</div>";
    }

}

<?php
abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    protected $request;

    protected static $baseParams = [
        'query' => [],
        'id' => null
    ];

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function setUp()
    {
        parent::setUp();

        //Initialize request object
        $this->request = new \Illuminate\Http\Request();
    }
}
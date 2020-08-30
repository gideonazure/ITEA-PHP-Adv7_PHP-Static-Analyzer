<?php

namespace ITEA\PhpStaticAnalyzer\Tests;

/**
 * Test class for displaying the result of the work ClassByNameAnalyzer
 *
 * @author Alexey Sk <gid.azure@gmail.com>
 */
final class AnalyzerTester
{
    private $priv_prop_1;
    private $priv_prop_2;

    public $pub_prop_1;
    public $pub_prop_2;
    public $pub_prop_3;
    public $pub_prop_4;

    protected $prot_prop_1;
    protected $prot_prop_2;
    protected $prot_prop_3;


    public function __construct(
        $priv_prop_1,
        $priv_prop_2,
        $pub_prop_1,
        $pub_prop_2,
        $pub_prop_3,
        $pub_prop_4,
        $prot_prop_1,
        $prot_prop_2,
        $prot_prop_3
    )
    {
        $this->priv_prop_1 = $priv_prop_1;
        $this->priv_prop_2 = $priv_prop_2;
        $this->pub_prop_1 = $pub_prop_1;
        $this->pub_prop_2 = $pub_prop_2;
        $this->pub_prop_3 = $pub_prop_3;
        $this->pub_prop_4 = $pub_prop_4;
        $this->prot_prop_1 = $prot_prop_1;
        $this->prot_prop_2 = $prot_prop_2;
        $this->prot_prop_3 = $prot_prop_3;
    }


    public function pub_method_1()
    {
        return 1;
    }


    public function pub_method_2()
    {
        return 2;
    }


    private function priv_method_1()
    {
        return 1;
    }


    private function priv_method_2()
    {
        return 2;
    }


    private function priv_method_3()
    {
        return 3;
    }


    protected function prot_method_1()
    {
        return 1;
    }


    protected function prot_method_2()
    {
        return 2;
    }

}
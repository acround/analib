<?php

namespace analib\Core\Build\Ora;

/**
 * Description of OraPackageBuilder
 *
 * @author acround
 */
class OraPackageBuilder
{

    protected string $name;
    protected string $title;
    private bool $ext;
    protected array $procedures;

    public function __construct($name, $title = '')
    {
        $this->name = $name;
        $this->title = $title;
    }

    public static function create($name, $title = null): OraPackageBuilder
    {
        return new self($name, $title);
    }

    /**
     *
     * @param boolean $ext
     * @return OraPackageBuilder
     */
    public function setExt(bool $ext): OraPackageBuilder
    {
        $this->ext = $ext;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addProcedure(OraProcedureBuilder $proc)
    {
        $this->procedures[$proc->getName()] = $proc;
    }

    public function dump(&$procNames): string
    {
        /* @var $proc OraProcedureBuilder */
        ksort($this->procedures);
        $name = $this->ext ? 'OraExtPackage' : 'OraPackage';
        $out = "<?php\n\n";
        $out .= "/**\n";
        $out .= " * class " . $this->name . "\n";
        if ($this->title) {
            $out .= " * " . iconv(OraModelBuilder::CHARSET_IN, OraModelBuilder::CHARSET_OUT, $this->title) . "\n";
        }
        $out .= " */\n";
        $out .= "class " . $this->name . " extends " . $name . " {\n\n";
        $out .= "	/**\n";
        $out .= "	 * @return " . $this->name . "\n";
        $out .= "	 */\n";
        $out .= "	public static function create() {\n";
        $out .= "		return new " . $this->name . "();\n";
        $out .= "	}\n";
        $out .= "\n";
        $procNames = array();
        foreach ($this->procedures as $proc) {
            $procNames[$proc->getName()] = $proc->getName();
            $out .= $proc->dump();
        }
        $out .= "}\n";
        /* 		$out .= "?>\n";
         *
         */
        return $out;
    }

}

import com.intellij.database.model.DasTable
import com.intellij.database.util.Case
import com.intellij.database.util.DasUtil

/*
 * Available context bindings:
 *   SELECTION   Iterable<DasObject>
 *   PROJECT     project
 *   FILES       files helper
 */

packageName = "App\\Biz;"
typeMapping = [
  (~/(?i)int/)                       : "int",
  (~/(?i)float|double|decimal|real/) : "float",
  (~/(?i)enum\('0', '1'\)/)          : "bool",
  (~/(?i)enum\('1', '0'\)/)          : "bool",
  (~/(?i)/)                          : "string"
]

FILES.chooseDirectoryAndSave("Choose directory", "Choose where to store generated files") { dir ->
  SELECTION.filter { it instanceof DasTable }.each { generate(it, dir) }
}

def generate(table, dir) {
  def className = javaName(table.getName(), true)
  def fields = calcFields(table)
  new File(dir, className + "Biz.php").withPrintWriter { out -> generate(out, className, fields) }
}

def generate(out, className, fields) {
  out.println "<?php"
  out.println ""
  out.println "namespace $packageName"
  out.println ""
  out.println "use Annotation\\BizJson;"
  out.println "use Annotation\\Serialize;"
  out.println ""
  out.println "class ${className}Biz extends \\iyoule\\BizSpace\\Biz {"
  out.println ""
  fields.each() {
    if (it.annos != "") out.println "    ${it.annos}"

    out.println "    /**"
    out.println "     * ${it.cmt}"
    out.println "     * @var ${it.type}"
    out.println "     * @Serialize("
    out.println "     *     decode=@BizJson(field={\"${it.col}\"}, type=\"${it.type}\", require=\"false\", hidden=\"false\"), "
    out.println "     *     encode=@BizJson(field={\"${it.col}\"}, type=\"${it.type}\", require=\"false\", hidden=\"false\"), "
    out.println "     *     dbcode=@BizJson(field={\"${it.col}\"}, type=\"${it.type}\", require=\"false\", hidden=\"false\")"
    out.println "     * )"
    out.println "     */"
    out.println "    protected \$${it.name};"
    out.println ""
  }
  out.println ""
  fields.each() {
    out.println ""
    out.println "    public function get${it.name.capitalize()}(): ?${it.type}"
    out.println "    {"
    out.println "        return \$this->${it.name};"
    out.println "    }"
    out.println ""
    out.println "    public function set${it.name.capitalize()}(${it.type} \$${it.name}): void "
    out.println "    {"
    out.println "        \$this->${it.name} = \$${it.name};"
    out.println "    }"
    out.println ""
  }
  out.println "}"
}

def calcFields(table) {
  DasUtil.getColumns(table).reduce([]) { fields, col ->
    def spec = Case.LOWER.apply(col.getDataType().getSpecification())
    def typeStr = typeMapping.find { p, t -> p.matcher(spec).find() }.value
    fields += [[
                 name : javaName(col.getName(), false),
                 col : col.getName(),
                 cmt : col.getComment(),
                 type : typeStr,
                 annos: ""]]
  }
}

def javaName(str, capitalize) {
  def s = com.intellij.psi.codeStyle.NameUtil.splitNameIntoWords(str)
    .collect { Case.LOWER.apply(it).capitalize() }
    .join("")
    .replaceAll(/[^\p{javaJavaIdentifierPart}[_]]/, "_")
  capitalize || s.length() == 1? s : Case.LOWER.apply(s[0]) + s[1..-1]
}

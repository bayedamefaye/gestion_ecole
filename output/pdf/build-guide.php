<?php
$src = file_get_contents(__DIR__.'/guide-complet-projet-school-kadija.html');
preg_match_all('~<section class="(?:cover|page)">(.*?)</section>~si', $src, $matches);
$pages = [];
foreach ($matches[1] as $section) {
  $section = preg_replace('~</(h1|h2|h3|p|li|tr|div|ol|ul|table)>~i', "\n", $section);
  $section = preg_replace('~<br\s*/?>~i', "\n", $section);
  $section = preg_replace('~</t[hd]>~i', ' | ', $section);
  $text = html_entity_decode(strip_tags($section), ENT_QUOTES | ENT_HTML5, 'UTF-8');
  $text = preg_replace('/[\t ]+/', ' ', $text);
  $text = preg_replace('/ *\n */', "\n", $text);
  $lines = [];
  foreach (explode("\n", $text) as $para) {
    $para = trim($para);
    if ($para === '') { $lines[]=''; continue; }
    foreach (explode("\n", wordwrap($para, 92, "\n", false)) as $line) $lines[]=$line;
  }
  foreach (array_chunk($lines, 52) as $chunk) $pages[]=$chunk;
}
$objs=[]; $push=function($s)use(&$objs){$objs[]=$s;return count($objs);};
$catalog=$push(''); $root=$push('');
$f1=$push('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>');
$f2=$push('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>');
$kids=[]; $total=count($pages);
$esc=function($s){$v=iconv('UTF-8','Windows-1252//TRANSLIT',$s);return str_replace(['\\','(',')'],['\\\\','\\(','\\)'],$v);};
foreach($pages as $pi=>$lines){
  $c="0.09 0.17 0.29 rg 0 820 595 22 re f\n";
  $c.="BT /F2 15 Tf 1 1 1 rg 46 788 Tm (Guide complet du code - School Kadija) Tj ET\n";
  $y=760;
  foreach($lines as $li=>$line){
    if($line===''){ $y-=6; continue; }
    $bold=(preg_match('/^(SCHOOL KADIJA|[0-9]+\\.|Comprendre le code|Guide en français|Résumé mental|À ne pas confondre|À retenir|Important pour les rôles)/u',$line)===1);
    $font=$bold?'F2':'F1'; $size=$bold?10:9;
    $c.="BT /$font $size Tf 0.13 0.19 0.29 rg 1 0 0 1 46 $y Tm (".$esc($line).") Tj ET\n";
    $y-=12.2;
  }
  $c.="0.83 0.87 0.91 rg 46 39 503 0.7 re f\nBT /F1 8 Tf 0.4 0.45 0.52 rg 46 24 Tm (School Kadija | Guide de lecture du projet) Tj ET\n";
  $n=$pi+1; $c.="BT /F1 8 Tf 0.4 0.45 0.52 rg 510 24 Tm (".$n." / ".$total.") Tj ET\n";
  $stream="<< /Length ".strlen($c)." >>\nstream\n$c\nendstream";
  $sid=$push($stream);
  $kid=$push("<< /Type /Page /Parent $root 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 $f1 0 R /F2 $f2 0 R >> >> /Contents $sid 0 R >>");
  $kids[]="$kid 0 R";
}
$objs[$catalog-1]="<< /Type /Catalog /Pages $root 0 R >>";
$objs[$root-1]='<< /Type /Pages /Kids ['.implode(' ',$kids).'] /Count '.count($kids).' >>';
$pdf="%PDF-1.4\n%\xE2\xE3\xCF\xD3\n"; $offs=[0];
foreach($objs as $i=>$o){$offs[]=strlen($pdf);$pdf.=($i+1)." 0 obj\n$o\nendobj\n";}
$xref=strlen($pdf); $pdf.="xref\n0 ".(count($objs)+1)."\n0000000000 65535 f \n";
for($i=1;$i<count($offs);$i++)$pdf.=sprintf('%010d 00000 n ',$offs[$i])."\n";
$pdf.="trailer\n<< /Size ".(count($objs)+1)." /Root $catalog 0 R >>\nstartxref\n$xref\n%%EOF\n";
file_put_contents(__DIR__.'/guide-complet-projet-school-kadija.pdf',$pdf);
echo 'Pages: '.count($pages).PHP_EOL;
?>

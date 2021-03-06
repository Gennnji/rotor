<?php //show_title('Функция format_num'); ?>

Форматирует число и устанавливает цвет текста (Доступно с версии 3.0.0)<br><br>

<pre class="d">
<b>format_num</b>(
	int num = 0
);
</pre><br>

<b>Параметры функции</b><br>

<b>num</b> - Число, eсли более нуля, то устанавливается зеленый цвет текста, если менее - красный, при нуле цвет не устанавливается. По умолчанию: 0<br><br>

<b>Примеры использования</b><br>

<?php
echo bbCode(check('[code]<?php
echo formatNum(5); /* <span style="color:#00aa00">+5</span> */
echo formatNum(-3); /* <span style="color:#ff0000">-5</span> */
echo formatNum(0); /* 0 */
?>[/code]'));
?>

<br>
<i class="fa fa-arrow-circle-left"></i> <a href="/files/docs">Вернуться</a><br>

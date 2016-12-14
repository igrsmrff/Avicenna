$(document).ready(function(){
    $('.print-btn').click(function(){
        var printing_css='<style media="print"> body{margin: 10mm 15mm 10mm 15mm;} @page {size: auto;margin: 0mm;}</style>';
        var html_to_print=printing_css+$('.a4').html();
        var iframe=$('<iframe id="print_frame">');
        $('body').append(iframe);
        var doc = $('#print_frame')[0].contentDocument || $('#print_frame')[0].contentWindow.document;
        var win = $('#print_frame')[0].contentWindow || $('#print_frame')[0];
        doc.getElementsByTagName('body')[0].innerHTML=html_to_print;
        win.print();
        $('iframe').remove();
    })
});
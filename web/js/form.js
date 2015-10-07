$(window).ready(function(){
    $('#form').submit(
        function(){
            var data = $(this).serializeArray();
            $.ajax({
                method: 'POST',
                data: data,
                success: function(r){
                    var ans = JSON.parse(r);
                    if(!ans.status){
                        for(var i in ans.errors) {
                            $('#' + i).css({border: '1px solid red'});
                        }
                    } else {
                        $('input').val('').css({border: ''});
                        alert('Сохранено');
                    }
                }
            });
            return false;
        }
    );
});
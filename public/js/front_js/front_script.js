$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    // $("#sort").on('change',function(){
    //     this.form.submit();
    // })
    $("#sort").on('change',function(){
        var sort =$(this).val();
        var url =$("#url").val();
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var fit = get_filter("fit");
        var pattern = get_filter("pattern");
        var occasion = get_filter("occasion");
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion,fit:fit,pattern:pattern,occasion:occasion,sort:sort,url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        })        
    });

    function get_filter(class_name) {
        var filter = [];
        $('.' + class_name +':checked').each(function(){
            filter.push($(this).val());
        });
        return filter;
    }

    $(".fabric").on('click',function(){
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var fit = get_filter("fit");
        var pattern = get_filter("pattern");
        var occasion = get_filter("occasion");
        var sort = $("#sort option:selected").text();
        var url = $("#url").val();
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion, sort:sort, url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        }) 
        

    });

    $(".sleeve").on('click',function(){
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var fit = get_filter("fit");
        var pattern = get_filter("pattern");
        var occasion = get_filter("occasion");
        var sort = $("#sort option:selected").text();
        var url = $("#url").val();
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion, sort:sort, url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        }) 
        

    })

    $(".fit").on('click',function(){
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var fit = get_filter("fit");
        var pattern = get_filter("pattern");
        var occasion = get_filter("occasion");
        var sort = $("#sort option:selected").text();
        var url = $("#url").val();
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion, sort:sort, url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        }) 
        

    })

    $(".pattern").on('click',function(){
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var fit = get_filter("fit");
        var pattern = get_filter("pattern");
        var occasion = get_filter("occasion");
        var sort = $("#sort option:selected").text();
        var url = $("#url").val();
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion, sort:sort, url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        }) 
        

    })

    $(".occasion").on('click',function(){
        var fabric = get_filter("fabric");
        var sleeve = get_filter("sleeve");
        var fit = get_filter("fit");
        var pattern = get_filter("pattern");
        var occasion = get_filter("occasion");
        var sort = $("#sort option:selected").text();
        var url = $("#url").val();
        $.ajax({
            url:url,
            method:"post",
            data:{fabric:fabric,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion, sort:sort, url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        }) 
        

    });

    $("#getPrice").change(function(){
        var size = $(this).val();
        if (size=="") {
            alert('please select size');
            return false;
        }
        var product_id = $(this).attr("product-id");
        $.ajax({
            url:'/get-product-price',
            data:{size:size,product_id:product_id},
            type:'post',
            success:function(resp){
                $(".getAttrPrice").html("$" + resp)
            },error:function(){
                alert("Error");
            }
        })
    });

});
$(document).ready(function(){

    // csrf token for ajax header request
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
            if (resp['discount'] > 0) {
                $(".getAttrPrice").html("<del>$" + resp['product_price']+ "</del> $" + resp['final_price']);
            }else{
                $(".getAttrPrice").html("$" + resp['product_price']);
            }
            },error:function(){
                alert("Error");
            }
        })
    });

    //update Cart Items dynamically
    $(document).on('click','.btnItemUpdate', function(){
        if($(this).hasClass('qtyMinus')){
            // if qtyMinus button gets clicked by user
            var quantity = $(this).prev().val();
            if(quantity <= 1){
                alert("Item quantity must be 1 or greater!");
                return false;
            }else{
                new_qty = parseInt(quantity)-1;
            }
        }
        if($(this).hasClass('qtyPlus')){
            // if qtyPlus button gets clicked by user
            var quantity = $(this).prev().prev().val();
            new_qty = parseInt(quantity)+1;
        }
            var cartid = $(this).data('cartid');
            $.ajax({
                data:{"cartid":cartid,"qty":new_qty},
                url:'/update-cart-item-qty',
                type:'post',
                success:function(resp){
                    alert(resp.status);
                    if (resp.status==false) {
                        alert("Product Stock is not Available");
                    }
                    $("#AppendCartItems").html(resp.view);
                },
                error:function(){
                    alert("Error");
                }
            });
    });

    //Delete Cart Items dynamically
    $(document).on('click','.btnItemDelete', function(){
            var cartid = $(this).data('cartid');
            var result = confirm("Want to deletethis cart item");
            if (result) {
                $.ajax({
                    data:{"cartid":cartid},
                    url:'/delete-cart-item',
                    type:'post',
                    success:function(resp){
                        $("#AppendCartItems").html(resp.view);
                    },
                    error:function(){
                        alert("Error");
                    }
                });
            }
    });

    
		// validate register form on keyup and submit
		$("#registerForm").validate({
			rules: {
				name: "required",
				mobile: {
					required: true,
					minlength: 10,
					maxlength: 10,
					digits: true
				},
                email: {
                    required: true,
                    email: true,
                    remote: "check-email"
                },
				password: {
					required: true,
					minlength: 6
				},
			},
			messages: {
				name: "Please enter your Name",
				mobile: {
					required: "Please enter a mobile",
					minlength: "Your mobile must consist of 10 characters",
					maxlength: "Your mobile must consist of 10 characters",
					digits: "Please enter your valid mobile"
				},
                email: {
                    required: "Please enter a valid email address",
                    email: "Please enter your valid email",
                    remote: "Email already exist"

                },
				password: {
					required: "Please choose your password",
					minlength: "Your password must be at least 6 characters long"
				},
			}
		});

		// validate Login form on keyup and submit
		$("#loginForm").validate({
			rules: {
                email: {
                    required: true,
                    email: true
                },
				password: {
					required: true,
					minlength: 6
				},
			},
			messages: {
                email: {
                    required: "Please enter a valid email address",
                    email: "Please enter your valid email"
                },
				password: {
					required: "Please enter your password",
					minlength: "Your password must be at least 6 characters long"
				},
			}
		});
});
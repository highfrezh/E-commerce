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

    $(".brand").on('click',function(){
        var brand = get_filter("brand");
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
            data:{brand:brand,fabric:fabric,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion, sort:sort, url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        }) 
        

    });

    $(".fabric").on('click',function(){
        var brand = get_filter("brand");
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
            data:{fabric:fabric,brand:brand,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion, sort:sort, url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        }) 
        

    });

    $(".sleeve").on('click',function(){
        var brand = get_filter("brand");
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
            data:{fabric:fabric,brand:brand,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion, sort:sort, url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        }) 
        

    })

    $(".fit").on('click',function(){
        var brand = get_filter("brand");
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
            data:{fabric:fabric,brand:brand,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion, sort:sort, url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        }) 
        

    })

    $(".pattern").on('click',function(){
        var brand = get_filter("brand");
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
            data:{fabric:fabric,brand:brand,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion, sort:sort, url:url},
            success:function(data){
                $('.filter_products').html(data);
            }
        }) 
        

    })

    $(".occasion").on('click',function(){
        var brand = get_filter("brand");
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
            data:{fabric:fabric,brand:brand,sleeve:sleeve,fit:fit,pattern:pattern,occasion:occasion, sort:sort, url:url},
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
                $(".mainCurrencyPrice").hide();
            if (resp['discount'] > 0) {
                $(".getAttrPrice").html("<del>$" + resp['product_price']+ "</del> $" + resp['final_price'] + "<br>" + resp['currency'] );
            }else{
                $(".getAttrPrice").html("$" + resp['product_price'] + resp['currency']);
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
                    if (resp.status==false) {
                        alert("Product Stock is not Available");
                    }
                    $(".totalCartItems").html(resp.totalCartItems);
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
            var result = confirm("Want to delete this cart item");
            if (result) {
                $.ajax({
                    data:{"cartid":cartid},
                    url:'/delete-cart-item',
                    type:'post',
                    success:function(resp){
                        $(".totalCartItems").html(resp.totalCartItems);
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
					maxlength: 20,
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
					maxlength: "Your mobile must consist of 20 characters",
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

        // validate account form on keyup and submit
		$("#accountForm").validate({
			rules: {
				name: "required",
				mobile: {
					required: true,
					minlength: 10,
					maxlength: 11,
					digits: true
				},
			},
			messages: {
				name: "Please enter your Name",
				mobile: {
					required: "Please enter a mobile",
					minlength: "Your mobile must consist of 10 characters",
					maxlength: "Your mobile must consist of 10 characters",
					digits: "Please enter your valid mobile"
				}
			}
		});

        // validate change password form on keyup and submit
		$("#passwordForm").validate({
			rules: {
				current_pwd: {
					required: true,
					minlength: 6,
					maxlength: 20
				},
				new_pwd: {
					required: true,
					minlength: 6,
					maxlength: 20
				},
				confirm_pwd: {
					required: true,
					minlength: 6,
					maxlength: 20,
                    equalTo:"#new_pwd"
				},
			}
		});

        //Check Current User Password
        $("#current_pwd").keyup(function(){
            var current_pwd = $(this).val();
            $.ajax({
                type: 'post',
                url: '/check-user-pwd',
                data:{current_pwd:current_pwd},
                success:function(resp) {
                    if(resp == "false"){
                        $("#chkPwd").html("<font color='red'>Current Password is Incorrect</font>");
                    }else{
                        $("#chkPwd").html("<font color='green'>Current Password is Correct</font>");
                    }
                },error:function() {
                    alert("Error");
                }
            })
        });

        //Apply Coupon 
        $("#ApplyCoupon").submit(function(){
            var user = $(this).attr("user");
            if(user != 1){
                alert("Please login to apply Coupon");
                return false;
            }
            var code = $("#code").val();
            $.ajax({
                type: 'post',
                data: {code:code},
                url:'/apply-coupon',
                success:function(resp){
                    if (resp.message != "") {
                        alert(resp.message);
                    } 
                    $(".totalCartItems").html(resp.totalCartItems);
                    $("#AppendCartItems").html(resp.view); 
                    if(resp.couponAmount >=0){
                        $(".couponAmount").text("$"+resp.couponAmount);
                    }else{
                        $(".couponAmount").text("$0");
                    }
                    if(resp.grand_total >=0){
                        $(".grand_total").text("$"+resp.grand_total);
                    }
                },
                error:function(){
                    alert("Error");
                }
            })

        });

        //Comfirm delete delivery address
        $(document).on('click','.addressDelete', function(){
            var result = confirm("Want to delete this Address?");
            if(!result){
                return false;
            }
        });

        //Calculate Shipping Charges and Update Grand total
        $("input[name=address_id]").bind('change',function(){
            var shipping_charges = $(this).attr("shipping_charges");
            var gst_charges = $(this).attr("gst_charges");
            var total_price = $(this).attr("total_price");
            var coupon_amount = $(this).attr("coupon_amount");
            if(coupon_amount == ""){
                coupon_amount = 0; 
            }
            $(".shipping_charges").html("$"+shipping_charges);
            $(".gst_charges").html("$"+gst_charges);
            var grand_total = parseInt(total_price) + parseInt(shipping_charges) + parseInt(gst_charges) - parseInt(coupon_amount);
            $(".grand_total").html("$"+ grand_total);
        });

        $(".userLogin").click(function(){
            alert('Login to add products in the wishlist');
        });

        //Update wishlist
        $(".updateWishlist").click(function(){
            var product_id = $(this).data('productid');
            $.ajax({
                type: "post",
                url:"/update-wishlist",
                data:{"product_id":product_id},
                success:function(resp){
                    if(resp.action == 'add'){
                        $('button[data-productid='+product_id+']').html('Wishlist <i class="icon-heart"></i>');
                        alert('Product added in your wishlist');
                    }else if(resp.action == 'remove'){
                        $('button[data-productid='+product_id+']').html('Wishlist <i class="icon-heart-empty"></i>');
                        alert('Product removed from your wishlist');
                    }
                },error:function(){
                    alert("Error");
                }
            });
        });

        //Delete Wishlist Item
        $(document).on('click','.wishlistItemDelete',function(){
            var wishlistid = $(this).data('wishlistid');
            $.ajax({
                type: 'post',
                url:'/delete-wishlist-item',
                data:{'wishlistid':wishlistid},
                success:function(resp){
                    $(".totalWishlistItems").html(resp.totalWishlistItems);
                    $("#AppendWishlistItems").html(resp.view);
                },
                error:function(){
                    alert("Error");
                }
            });
        });

        //Concel order Confirm
        $(document).on('click','.btnCancelOrder',function(){
            var reason = $("#cancelReason").val();
            if(reason==""){
                alert("Please select Reason for cancelling the Order");
                return false;
            }
            var result = confirm("Want to cancel this Order?");
            if(!result){
                return false;
            }
        }) ; 

        //Return order Confirm
        $(document).on('click','.btnReturnOrder',function(){
            var return_exchange = $("#returnExchange").val();
            if(return_exchange == ""){
                alert("Please select if you want to return or exchange?");
                return false;
            }
            var product = $("#returnProduct").val();
            if(product==""){
                alert("Please select Product to return");
                return false;
            }
            var reason = $("#returnReason").val();
            if(reason==""){
                alert("Please select Reason for returning the Order");
                return false;
            }
            var result = confirm("Want to return this Order?");
            if(!result){
                return false;
            }
        }) ; 

        //Show and hide required product size
        $(".productSizes").hide();
        $("#returnExchange").change(function(){
            var return_exchange = $(this).val();
            if (return_exchange == "Exchange") {
                $(".productSizes").show();
            }else{
                $(".productSizes").hide();
            }
        });

        //Getting other product size on change
        $("#returnProduct").change(function(){
            var product_info = $(this).val();
            var return_exchange = $("#returnExchange").val();
            if (return_exchange == "Exchange") {
                $.ajax({
                    type: 'post',
                    url: '/get-product-sizes',
                    data:{product_info:product_info},
                    success:function(resp){
                        $("#productSizes").html(resp);
                    },
                    error:function(){
                        alert("Error");
                    }
                })
            }
        });        
});

//Add newsletter subscriber
        function addSubscriber() {
            var subscriber_email = $("#subscriber_email").val();
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (regex.test(subscriber_email) == false) {
                alert('Pleasen enter valid Email!');
                return false;                
            }
            $.ajax({
                type: 'post',
                url: '/add-subscriber-email',
                data:{subscriber_email:subscriber_email},
                success:function(resp){
                    if(resp == "exists"){
                        alert("Subscriber email already exists!");
                    }else if(resp == "inserted"){
                        alert("Thanks for Subscribing!");
                    }
                },
                error:function(){
                    alert("Error");
                }
            });
        }
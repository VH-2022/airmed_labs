i = 120;
/*Nishit family module start*/
$("#type_user_self").click(function () {
    $("#family_div").attr("style", "display:none;");
});
$("#type_user_family").click(function () {
    $("#family_div").removeAttr("style");
});
$("#job_address").change(function () {
    $("#user_address").val(this.value);
});

var today = new Date();
var dd = today.getDate();
var mm = today.getMonth() + 1; //January is 0!
var yyyy = today.getFullYear();

if (dd < 10) {
    dd = '0' + dd
}

if (mm < 10) {
    mm = '0' + mm
}

today = dd + '/' + mm + '/' + yyyy;
$('#phlebo_shedule_date').datepicker({
    format: 'dd/mm/yyyy',
    todayHighlight: true,
    //autoclose: true,
    //startDate: today,
    endDate: '+3m'
});
$('#phlebo_shedule_date').change(function () {
    $("#contener_3_error").html("");
    $.ajax({
        url: base_url() + 'phlebo-api_v2/get_phlebo_schedule',
        type: 'post',
        data: {bdate: this.value, },
        beforeSend: function () {
            $("#phlebo_shedule").html('<span><img src="' + base_url() + 'upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>');
            //$("#send_opt_1").attr("disabled", "disabled");
        },
        success: function (data) {
            var json_data = JSON.parse(data);
            if (json_data.status == 1) {
                $("#phlebo_shedule").html("");
                for (var i = 0; i < json_data.data.length; i++) {
                    if (json_data.data[i].booking_status == 'Available') {
                        $("#phlebo_shedule").append("<a href='javascript:void(0);' id='time_slot_" + json_data.data[i].id + "' onclick='get_select_time(this," + json_data.data[i].time_slot_fk + ");'><p>" + json_data.data[i].start_time + " TO " + json_data.data[i].end_time + "<br>(" + json_data.data[i].booking_status + ")</p></a>");
                    } else {
                        $("#phlebo_shedule").append("<a href='javascript:void(0);'><p>" + json_data.data[i].start_time + " TO " + json_data.data[i].end_time + "<br>(" + json_data.data[i].booking_status + ")</p></a>");
                    }
                    $("#booking_slot").val("");
                }
                $("#phlebo_shedule").append('<div class="clearfix"></div><div class="form-group"><label for="message-text" class="form-control-label">Request to consider as emergency:</label><input type="checkbox" value="emergency" onclick="check_emergency(this);" value="emergency" id="as_emergency"></div>');
            } else {
                if (json_data.error_msg == 'Time slot unavailable.') {
                    $("#phlebo_shedule").empty();
                    $("#phlebo_shedule").append('<div class="form-group"><label for="message-text" class="form-control-label">Request to consider as emergency:-</label><input type="checkbox" value="emergency" onclick="check_emergency(this);" value="emergency" id="as_emergency"></div>' + "<span style='color:red;'>" + json_data.error_msg + "</span>");
                } else {
                    $("#phlebo_shedule").html("<span style='color:red;'>" + json_data.error_msg + "</span>");
                }
            }
        },
        error: function (jqXhr) {
            $("#phlebo_shedule").html("");
        },
        complete: function () {
            //$("#shedule_loader_div").attr("style", "display:none;");
            //$("#send_opt_1").removeAttr("disabled");
        },
    });
});
$("#phlebo_shedule_date").val(t_date);
$("#phlebo_shedule_date").trigger("change");
$("#add_family_member").click(function () {
    $("#f_error").html("");
    var f_name = $("#f_name").val();
    var f_mobile = $("#f_phone").val();
    var f_email = $("#f_email").val();
    var f_relation = $("#f_relation").val();
    if (f_name.trim() != '' && f_relation.trim() != '') {
        f_cnt = 0;
        if (f_mobile.trim() != '') {
            if (checkmobile(f_mobile) == true) {

            } else {
                f_cnt = 1;
                $('#f_error').html("Invalid Mobile Number.");
            }
        }
        if (f_email.trim() != '') {
            if (checkemail(f_email) == true) {

            } else {
                f_cnt = 1;
                $('#f_error').html("Invalid Email.");
            }
        }
        if (f_cnt == 0) {
            $.ajax({
                url: base_url() + 'phlebo-api_v2/add_family_member',
                type: 'post',
                data: {uid: uid, name: f_name, relation_fk: f_relation, email: f_email, phone: f_mobile},
                beforeSend: function () {
                    $("#f_error").html('<span><img src="' + base_url() + 'upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>');
                    //$("#send_opt_1").attr("disabled", "disabled");
                },
                success: function (data) {
                    var jsondata = JSON.parse(data);
                    if (jsondata.status == 0) {
                        $("#f_error").html(jsondata.error_msg);
                    } else {
                        $("#family_per").append("<option value='" + jsondata.data[0].id + "'>" + f_name + "(" + jsondata.data[0].relation_name + ")</option>");
                        setTimeout(function () {
                            $("#family_per").select2();
                            $("#family_per").val(jsondata.data[0].id).trigger("change");
                        }, 1000);
                        $("#f_name").val("");
                        $("#f_phone").val("");
                        $("#f_email").val("");
                        $("#f_relation").val("");
                    }
                    $("#f_error").html('');
                },
                error: function (jqXhr) {
                    $("#f_error").html("Oops somthing wrong Tryagain!.");
                    $("#f_error").html('');
                },
                complete: function () {
                    $("#f_error").html('');
                }
            });
        }
    } else {
        $("#f_error").html("Name and relation are required.");
    }
});
function check_emergency(val) {
    if (val.checked == true) {
        $("#booking_slot").val(val.value);
    }
    if (val.checked == false) {
        $("#booking_slot").val("");
    }
    var elms = document.getElementById("phlebo_shedule").getElementsByTagName("a");
    for (var i = 0; i < elms.length; i++) {
        elms[i].setAttribute("style", "");
    }
}
function get_select_time(val, id1) {
    var id = val.id;
    var elms = document.getElementById("phlebo_shedule").getElementsByTagName("a");
    for (var i = 0; i < elms.length; i++) {
        elms[i].setAttribute("style", "");
    }
    $("#" + id).attr("style", "background: #eeeeee none repeat scroll 0 0; border: 1px solid #d01130;  color: #d01130;");
    $("#booking_slot").val(id1);
    $("#as_emergency").removeAttr("checked");
}
validation_step = 1;
function info_validation() {
    $("#contener_1_error").html("")
    $("#contener_2_error").html("")
    $("#contener_3_error").html("")
    if (validation_step == 1) {
        $("#final_book").html("Next");
        $("#contener_1").attr("style", "display:none;");
        $("#contener_2").attr("style", "");
        $("#contener_3").attr("style", "display:none;");
        $("#book_back").attr("style", "");
        validation_step = 2;
        return false;
    } else if (validation_step == 2) {
        var uaddress = $("#user_address").val();
        if (uaddress != '') {
            $("#contener_1").attr("style", "display:none;");
            $("#contener_2").attr("style", "display:none;");
            $("#contener_3").attr("style", "");
            $("#book_back").attr("style", "");
            $("#final_book").html("Book");
            validation_step = 3;
            return false;
        } else {
            $("#contener_2_error").html("Address is required.");
        }
    } else if (validation_step == 3) {
        $("#book_back").attr("style", "");
        var bookdate = $("#phlebo_shedule_date").val();
        if (bookdate != '') {
            var select_slot = $("#booking_slot").val();
            if (select_slot != '0' && select_slot != '') {
                if (typ == 0) {
                    typ = "self";
                    var crelation = "0";
                } else {
                    var crelation = typ;
                    typ = "family";
                }
                var uaddress = $("#user_address").val();
                $.ajax({
                    url: base_url() + 'phlebo-api_v2/save_booking_data',
                    type: 'post',
                    data: {uid: uid, type: typ, crelation: crelation, uaddress: uaddress, bookdate: bookdate, select_slot: select_slot},
                    beforeSend: function () {
                        $("#contener_3_error").html('<span><img src="' + base_url() + 'upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>');
                        //$("#send_opt_1").attr("disabled", "disabled");
                    },
                    success: function (data) { 
                        var jsondata = JSON.parse(data);
                        if (jsondata.status == 0) {
                            $("#contener_3_error").html(jsondata.error_msg);
                        } else {
                            window.location.href = base_url() + "active_packages/book/" + book_test_id + "/" + jsondata.data[0].id;
                            //alert(base_url() + "active_packages/book/" + book_test_id + "/" + jsondata.data[0].id); return false;
                        }
                        $("#contener_3_error").html('');
                    },
                    error: function (jqXhr) {
                        $("#contener_3_error").html("Oops somthing wrong Tryagain!.");
                        $("#contener_3_error").html('');
                    },
                    complete: function () {
                        $("#contener_3_error").html('');
                    }
                });
                //window.location.href=base_url()+'user_test_master/book_test/'+book_test_id;
            } else {
                $("#contener_3_error").html("Select time slot.");
            }
        } else {
            $("#contener_3_error").html("Select date and time.");
        }
    }

}
function back_info_validation() {
    if (validation_step == 1) {
        $("#book_back").attr("style", "display:none;");
    }
    if (validation_step == 2) {
        $("#contener_1").attr("style", "");
        $("#contener_2").attr("style", "display:none;");
        $("#book_back").attr("style", "display:none;");
        validation_step = 1;
    }
    if (validation_step == 3) {
        $("#contener_2").attr("style", "");
        $("#final_book").html("Next");
        //$("#book_back").attr("style", "display:none;");
        $("#contener_3").attr("style", "display:none;");
        validation_step = 2;
    }
}
function store_booking_info() {
    var typ = $("input[name='type_user_family']:checked").val();
    if (typ == "family") {
        var crelation = $("#family_per").val();
    } else {
        var crelation = "";
    }
    var uaddress = $("#user_address").val();
    var bookdate = $("#phlebo_shedule_date").val();
    var select_slot = $("#booking_slot").val();
    $.ajax({
        url: base_url() + 'phlebo-api_v2/store_booking_info',
        type: 'post',
        data: {typ: typ, crelation: crelation, uaddress: uaddress, bookdate: bookdate, select_slot: select_slot},
        beforeSend: function () {
            $("#phlebo_shedule").html('<span><img src="' + base_url() + 'upload/opc-ajax-loader.gif" style="height:28px;margin-left:10px"></span>');
            //$("#send_opt_1").attr("disabled", "disabled");
        },
        success: function (data) {
            var json_data = JSON.parse(data);
            if (json_data.status == 1) {
                $("#phlebo_shedule").html("");
                for (var i = 0; i < json_data.data.length; i++) {
                    $("#phlebo_shedule").append("<a href='javascript:void(0);' id='time_slot_" + json_data.data[i].id + "' onclick='get_select_time(this," + json_data.data[i].time_slot_fk + ");'><p>" + json_data.data[i].start_time + " TO " + json_data.data[i].end_time + "</p></a>");
                }
            } else {
                $("#phlebo_shedule").html("<span style='color:red;'>" + json_data.error_msg + "</span>");
            }
        },
        error: function (jqXhr) {
            $("#phlebo_shedule").html("");
        },
        complete: function () {
            //$("#shedule_loader_div").attr("style", "display:none;");
            //$("#send_opt_1").removeAttr("disabled");
        },
    });
}
/*Nishit family module end*/

$('.decimal').keyup(function () {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
})
function Validation() {
    var cnt = 0;
    $('#mobile_error').html('');
    $('#cod_error').html('');
    //$('#cod_radio').checked = 
    var mobile_id = $("#mobile_id").val();
    if (mobile_id == '') {
        $('#mobile_error').html('Please Enter Mobile Number!');
    } else if (checkmobile(mobile_id) == false) {
        $('#mobile_error').html('Invalid Number');
    } else if (mobile_id.length != 10) {
        $('#mobile_error').html('Enter Valid Number');
    } else if ($("#cod_radio").is(":checked") == false) {

        $('#cod_error').html('Please select Payment Mode');
    } else {
        $("#without_login").submit();
    }

}

emial_type = 0;
function uploads() {
    var email = document.getElementById("email").value;
    if (checkemail(email) == true) {
        $('#error_email').html(" ");
        $.post(base_url() + "user_master/check_email", {
            email: email
        }, function (data) {
            if (data == 'login') {
                $("#login_password").show();
                $("#register").hide();
                emial_type = 'login';
            } else if (data == 'register') {
                $("#register").show();
                $("#login_password").hide();
                emial_type = 'register';
            }
        });
    } else {
        emial_type = 0;
        $('#error_email').html("Invalid Email.");
    }
}
function validmobile() {
    var mobile = document.getElementById("mobile").value;
    if (checkmobile(mobile) == true) {
        $('#error_mobile').html(" ");
    } else {
        emial_type = 0;
        $('#error_mobile').html("Invalid Mobile Number.");
    }
}
function checkemail(mail) {
    var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    if (filter.test(mail)) {
        return true;
    } else {
        return false;
    }
}
function checkmobile(mobile) {
    var filter = /^[0-9-+]+$/;
    var pattern = /^\d{10}$/;
    if (filter.test(mobile)) {
        if (pattern.test(mobile)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function vlidation_btn() {
    var all = [];
    if (emial_type == 0) {
        $('#add').each(function () {
            var add = $(this).val();
            $('#error_add').html("");
            if (add != '') {
                $('#error_add').html(" ");
            } else {
                all = 1;
                $('#error_add').html("Address is required.");
            }
        });
    }
    if (emial_type == 'login') {
        $('#logpass').each(function () {
            var logpass = $(this).val();
            $('#error_logpass').html("");
            if (logpass != '') {
                $('#error_logpass').html(" ");
            } else {
                all = 1;
                $('#error_logpass').html("Login Password is required.");
            }
        });
    }
    if (emial_type == 'register') {
        $('#add').each(function () {
            var add = $(this).val();
            $('#error_add').html("");
            if (add != '') {
                $('#error_add').html(" ");
            } else {
                all = 1;
                $('#error_add').html("Address is required.");
            }
        });
        $('#regpass').each(function () {
            var regpass = $(this).val();
            $('#error_regpass').html("");
            if (regpass != '') {
                $('#error_regpass').html(" ");
            } else {
                all = 1;
                $('#error_regpass').html("Password is required.");
            }
        });
        $('#regname').each(function () {
            var regname = $(this).val();
            $('#error_regname').html("");
            if (regname != '') {
                $('#error_regname').html(" ");
            } else {
                all = 1;
                $('#error_regname').html("Name is required.");
            }
        });
        $('#regconpass').each(function () {
            var regconpass = $(this).val();
            var regpass = $("#regpass").val();
            $('#error_regconpass').html("");
            if (regconpass != '') {
                $('#error_regconpass').html(" ");
            } else if (regconpass != regpass) {
                all = 1;
                $('#error_regconpass').html("Password and Conform Password is Dont Match.");
            } else {
                all = 1;
                $('#error_regconpass').html("Conform Password is required.");
            }
        });
    }
    if (all != '1') {
        $("#uploadform").submit();
    } else {
        return false;
    }
}



function send() {

    var name = document.getElementById('regname').value;
    var email = document.getElementById('email').value;
    var mobile = document.getElementById('mobile').value;
    var add = document.getElementById('add').value;
    var age = document.getElementById('age').value;
    var test = document.getElementById('testname').value;
    var logpass = document.getElementById('logpass').value;
    var regpass = document.getElementById('regpass').value;
    var regconpass = document.getElementById('regconpass').value;
    if (name != "" && email != "" && mobile != "" && add != "" && age != "" && test != "") {

        $.ajax({
            url: base_url() + "user_master/send_serch_test_email",
            type: 'post',
            data: {name: name, email: email, mobile: mobile, address: add, age: age, testname: test},
            success: function (data) {
                //     console.log("data"+data);
                document.getElementById('error_msg').innerHTML = "";
                document.getElementById('success_msg').innerHTML = "Thank You ! Your Request Sent..Our Representative will call you Shortly";
                document.getElementById('name').value = "";
                document.getElementById('email').value = "";
                document.getElementById('mobile').value = "";
                document.getElementById('add').value = "";
                document.getElementById('age').value = "";
            }

        });
    } else {

        document.getElementById('error_msg').innerHTML = "Please Insert All Fields";
        document.getElementById('success_msg').innerHTML = " ";
    }
}
//new counter for 2.00min															
function countdown(elementName, minutes, seconds)
{
    var element, endTime, hours, mins, msLeft, time;
    function twoDigits(n)
    {
        return (n <= 9 ? "0" + n : n);
    }

    function updateTimer()
    {
        msLeft = endTime - (+new Date);
        if (msLeft < 1000) {
            //element.innerHTML = "countdown's over!";
            $("#mycounter").attr("style", "display:none;");
            $("#resend_opt").attr("style", "");
            element.innerHTML = '';
        } else {
            $("#resend_opt").attr("style", "display:none;");
            time = new Date(msLeft);
            hours = time.getUTCHours();
            mins = time.getUTCMinutes();
            element.innerHTML = 'Resend OTP after <b>' + (hours ? hours + ':' + twoDigits(mins) : mins) + ':' + twoDigits(time.getUTCSeconds()) + ' </b>';
            setTimeout(updateTimer, time.getUTCMilliseconds() + 500);
        }
    }

    element = document.getElementById(elementName);
    endTime = (+new Date) + 1000 * (60 * minutes + seconds) + 500;
    updateTimer();
}
function check_otp() {
}
function resend_otp() {

    $("#otp_success").html("");
    $("#resend_opt").attr("style", "display:none;");
    $("#mycounter").attr("style", "");
    //$("#check_otp_div").attr("style", "display:none;");
    $("#otp_error").html("");
    var mb_no = $("#mobile_id").val();
    if (mb_no != "") {
        if (checkmobile(mb_no) == true) {
            $.ajax({
                url: base_url() + 'api_v2/add_opt_test',
                type: 'post',
                data: {mobile: mb_no, },
                beforeSend: function () {
                    $("#loader_div").attr("style", "");
                    $("#send_opt_1").attr("disabled", "disabled");
                },
                success: function (data) {
                    var jsondata = JSON.parse(data);
                    if (jsondata.status == 0) {
                        $("#otp_error").html(jsondata.error_msg);
                    } else {
                        $("#mb_no").attr("style", "display:none;");
                        $("#check_otp_div").attr("style", "");
                        //$("#resend_opt").attr("style", "");
                        //$("#mycounter").attr("style", "");
                        countdown("mycounter", 2, 0);
                        //onTimer();
                        $("#check_otp").prop("disabled", false);
                        $("#send_opt_1").attr("style", "display:none;");
                        $("#mobile_id").attr("readonly", "");
                        $("#check_otp").attr("style", "");
                        $("#otp_success").html("<br>OTP successfully send.");
                        //$("#resend_opt").attr("style", "");
                    }
                    //$("#city_wiae_price").html(data);
                },
                error: function (jqXhr) {
                    $("#resend_opt").attr("style", "");
                    //alert('Oops somthing wrong Tryagain!.');
                },
                complete: function () {
                    $("#loader_div").attr("style", "display:none;");
                    $("#send_opt_1").removeAttr("disabled");
                },
            });
        } else {
            $("#otp_error").html("Invalid Mobile Number");
        }
    } else {
        $("#otp_error").html("Please enter mobile number.");
    }
}
function test() {

    $("#otp_success").html("");
    $("#otp_error").html("");
    var mb_no = $("#mobile_id").val();
    var otp = $("#otp_val").val();
    $("#check_otp").prop("disabled", true);
    if (otp.trim() != '') {
        $.ajax({
            url: base_url() + 'api_v2/check_otp_test',
            type: 'post',
            data: {otp: otp, mobile: mb_no},
            success: function (data) {
                var jsondata = JSON.parse(data);
                if (jsondata.status == 0) {
                    $("#check_otp").prop("disabled", false);
                    $("#otp_error").html(jsondata.error_msg);
                } else {
                    $("#otp_success").html("<br>Verified.");
                    //$("#otp_val").attr("style", "display:none;");
                    $("#resend_opt").attr("style", "display:none;");
                    $("#mycounter").attr("style", "display:none;");
                    $("#otp_error").attr("style", "display:none;");
                    //$("#check_otp").attr("style", "display:none;");
                    //$("#keyid").attr("style", "display:none;");
                    //$("#mycounter").attr("style", "display:none;");
                    //$("#check_otp").attr("style", "display:none;");
                    $("#final_book").click();
                }
                //$("#city_wiae_price").html(data);
            },
            error: function (jqXhr) {
                $("#check_otp").prop("disabled", false);
                alert('Oops somthing wrong Tryagain!.');
            }
        });
    } else {
        console.log('vishal');
        $("#check_otp").prop("disabled", false);
        $("#otp_error").html("OTP field is required.");
    }
}
function remove_test(val) {
    if (confirm('Are you sure want to remove?')) {


        $("#li_id_" + val).remove();
        //alert(val

        setTimeout(function () {
            var hidden_sting = '';
            var hidden_string_name = '';
            var elems = document.getElementsByClassName('myAvailableTest');
            for (var i = 0; i < elems.length; i++) {
                var new_id = elems[i].id;
                new_id = new_id.split("_");
                var tag_name = elems[i].title;
                if (i == 0) {
                    hidden_sting = hidden_sting + new_id[1];
                    hidden_string_name = hidden_string_name + tag_name;
                } else {
                    hidden_sting = hidden_sting + "," + new_id[1];
                    hidden_string_name = hidden_string_name + "#" + tag_name;
                }
            }
            //console.log(hidden_sting+" final string");
            //console.log(hidden_string_name+" final string name");
            $('#selectedid').val(hidden_sting);
            $('#selectedname').val(hidden_string_name);
            //console.log(y1);
            //console.log(x1);
            var x1 = hidden_sting.split(',');
            var y1 = hidden_string_name.split('#');
            $.ajax({
                url: base_url() + "user_master/searching_test",
                type: 'post',
                data: {id: x1, name: y1},
                success: function (data) {
                    //     console.log("data"+data);
                    window.location = base_url() + "user_master/order_search";
                }
            });
        }, 1000);
    }
    ;
}
function add_token(id, name) {

    $('#vidyagames').tokenInput("add", {id: id, name: name});
    $('#' + id + '_add').css("display", "none");
    $('#' + id + '_close').css("display", "block");
    $('#btn_search').html('Book');
    return false;
}
function remove_token(id, name) {
    $('#vidyagames').tokenInput("remove", {id: id, name: name});
    $('#' + id + '_close').css("display", "none");
    $('#' + id + '_add').css("display", "block");
    return false;
}
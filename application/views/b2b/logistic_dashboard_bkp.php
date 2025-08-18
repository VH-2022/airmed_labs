<section class="content-header">
    <h1>
        Dashboard             
    </h1>

    <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
    <section class="content">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-sm-2">
                                <input type="text" name="start_date" placeholder="Select date" class="form-control datepicker-input" id="date"  value="<?= $start_date ?>" style="width:190px;" />
                            </div>

                            <div class="col-sm-2">
                                <input type="text" name="end_date" placeholder="End date" class="form-control datepicker-input" id="date1"  value="<?= $end_date ?>" style="width:190px;"/>
                            </div>
         
              <div class="col-sm-3">
                  <input type="submit" name="search" class="btn btn-success" value="Search" id="search_id" onclick="searchData();"/>
                            </div>
        </div> 
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Total Revenue</span>
                        <span class="info-box-number" id="total_price"></span>
                        <div class="progress">
                            <div style="width: 100%" class="progress-bar"></div>
                        </div>
                        <a href="#" style="color:white;"><span class="progress-description">
                                View More
                            </span></a>
                    </div><!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-teal">
                    <span class="info-box-icon"><i class="fa fa-eyedropper"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Total Samples</span>
                        <span class="info-box-number" id="total_sample"></span>
                        <div class="progress">
                            <div style="width: 100%" class="progress-bar"></div>
                        </div>
                        <a href="#" style="color:white;"><span class="progress-description">
                                View More
                            </span></a>
                    </div><!-- /.info-box-content -->
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-olive">
                    <span class="info-box-icon"><i class="fa fa-flask"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Today's Total Test</span>
                        <span class="info-box-number">100</span> 
                        <div class="progress">
                            <div style="width: 100%" class="progress-bar"></div>
                        </div>
                        <a href="#" style="color:white;"><span class="progress-description">
                                View More
                            </span></a>
                    </div><!-- /.info-box-content -->
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
</div>
</div>
<script src="<?php echo base_url();?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script> 
<script>
    $("#date").datepicker({
       dateFormat: 'dd/mm/yy' 
    }).datepicker("setDate", new Date());
    
    $('#date1').datepicker({
         dateFormat: 'dd/mm/yy'
    }).datepicker("setDate", new Date());
    </script>
    
    <script>
        function searchData(){
          var first_date = $('#date').val();
          var second_date = $('#date1').val();
          
          $.ajax({
              url:"<?php echo base_url();?>b2b/logistic/search_dashboard",
              method:"POST",
              data:{start_date:first_date,end_date:second_date},
                success:function(data){
                
                    var obj = JSON.parse(data);
                    var price = obj.price;
                    var sample = obj.total_sample;
                    if(sample == null){
                        Total_Sample = '0';
                    }else{
                        Total_Sample = sample;
                    }
                    if(price == null){
                        Total_price = '0';
                    }else{
                        Total_price = price;
                    }
        
                    $('#total_price').html('Rs.'+Total_price);
                    $('#total_sample').html(Total_Sample);
                }
          })
        }
    </script>
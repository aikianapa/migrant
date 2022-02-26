<html>

<div class="m-3" id="yongerReportRegs" wb-allow="admin">

    <nav class="nav navbar navbar-expand-md col">
        <h3 class="tx-bold tx-spacing--2 order-1">Отчёт по регистраторам</h3>
    </nav>


    <div>
        <wb-var month='{{date("Y-m")}}' />
        <wb-var month='{{_post.month}}' wb-if="'{{_post.month}}'>''" />
        <form class="row">
            <div class="col-sm-6">
                <div class="input-group">
                    <input class="form-control" type="month" name="month" value="{{_var.month}}">
                    <div class="input-group-append cursor-pointer">
                        <span class="input-group-text" id="getRep">Показать</span>
                    </div>
                </div>
            </div>
        </form>

        

        <div class="my-3">
            <wb-foreach wb="from=result">
                <div>
                    {{creator.first_name}} {{creator.last_name}} <small>{{creator.email}}</small>
                    <div>
                        <wb-foreach wb="count={{mds}}">
                            <div class="d-inline-block text-center bd wd-25">
                                <div class="bg-light bd-b">{{_ndx}}</div>
                                <div wb-if="'{{_parent.days.{{_ndx}}}}'>''">{{_parent.days.{{_ndx}}}}</div>
                                <div wb-if="'{{_parent.days.{{_ndx}}}}'==''">-</div>
                            </div>
                        </wb-foreach>
                    </div>
                </div>
            </wb-foreach>
        </div>
    </div>

    <script>
    $('#getRep').click(function(){
        let $form = $('#yongerReportRegs form');
        let month = $('#yongerReportRegs form [name=month]').val();
        if (month > '') {
            wbapp.post('/cms/ajax/form/docs/rep_reg',$form.serialize(),function(data){
                $('.content-body').html(data);
            })
        }
    })
    </script>
</div>

</html>
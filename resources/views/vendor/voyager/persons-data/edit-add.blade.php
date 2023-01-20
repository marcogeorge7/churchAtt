@extends('voyager::bread.edit-add')

@push('javascript')
    <script>
        $('document').ready(function () {

            $("input[name='national_id']").keypress(function () {
                if ($(this).val().length === 13) {

                    // CHECK MALE OR FEMALE

                    var text = $(this).val().toString(); //convert to string
                    var male = text.slice(-2); //gets last character
                    male = +(male);
                    if (male%2 == 1)
                        $("#option-male-female-1").attr('checked', true);
                    else
                        $("#option-male-female-0").attr('checked', true);


                    // SET DATE OF BIRTH

                    var g = text.slice(0,1);
                    g = parseInt(g);
                    var gen = g +17;
                    var year = text.slice(1, 3);
                    var mon = text.slice(3, 5);
                    var day = text.slice(5, 7);

                    $("input[name='birth_date").val(""+gen+year+"-"+mon+"-"+day+"");
                }
            });

            // show or hide items

            $("select[name='deacon_degree']").parent().hide();
            var deacon = $('#option-is-deacon-0').click(function () {
                $("select[name='deacon_degree']").parent().show();
            });
            var not_deacon = $('#option-is-deacon-1').click(function () {
                $("select[name='deacon_degree']").parent().hide();
            });

        })
    </script>
@endpush

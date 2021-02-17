<?php 


    use yii\bootstrap\ActiveForm;
    use yii\helpers\{
        Url,
        Html
    };
?>

<iframe id="test" width="100%" style="min-height: 85vh;margin-top: -22px;" src="https://new65334704.wazzup24.com/chat/?token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2NvdW50SWQiOjY1MzM0NzA0LCJ1c2VyR3VpZCI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDAwMDAwMDAwMCIsImludGVncmF0aW9uIjp7ImNybSI6ImFwaV92MiIsImd1aWQiOiIwMDI1ODQ0ZS04YzI5LTQzZmItYTMwMi02ODRkYmRjZjk0NWUiLCJ0eXBlIjo5LCJ1c2VySWQiOiIxMjAwMCIsInVzZXJOYW1lIjoiMiIsInZlcnNpb24iOjEsInNjb3BlIjoiZ2xvYmFsIiwiZmlsdGVyIjp7InZlcnNpb24iOjIsImNvbnRhY3RzIjpmYWxzZX0sInNob3dHcm91cENoYXRzIjpmYWxzZSwiY2hhbm5lbHNGaWx0ZXIiOltdfSwiaWF0IjoxNjEyODY0ODgzLCJleHAiOjE2MTI4OTM2ODN9.vL5HM_h0IstIKQN1Sn5ixr1nd30_LdKRxgxcy59jdfo">

</iframe>





<script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
        setTimeout(function () {
            console.log($('#test').contents().find('.chat-footer'));
        }, 2000);

    });
</script>



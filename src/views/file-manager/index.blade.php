<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>File Manager</title>
    <!-- Include our stylesheet -->
    <link href="/css/filemanager/styles.css?v=2" rel="stylesheet"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
          integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
<div class="messages"></div>
<?php $image_name = []; ?>
@if(Session::has('message'))
    <?php
        $image_name = Session::get('image_name');
    ?>
    <div class="response-message">{{Session::get('message')}}</div>
@elseif(Session::has('error'))
    <div class="response-message"> {{Session::get('error')}}</div>
@endif
<div class="filemanager">
    <div style="padding-right: 2.3em;">
        <span id="mySelected">
            {{--<i class="fas fa-check add-icon" id="file-selected"></i>--}}
            <button type="button" id="insert-btn" class="btn btn-theme-color btn-lg waves-effect filemanager-btn" style="display: none;">
                Insert
            </button>
        </span>
        <span id="myBtn">
            {{--<i class="fas fa-plus add-icon"></i>--}}
            <button type="button" class="btn btn-theme-color btn-lg waves-effect filemanager-btn">
                Add Folder
            </button>
        </span>
        <form action="/filemanager/upload" method="post" enctype="multipart/form-data" role="form" id="upload-form">
            {{csrf_field()}}
            <input type="hidden" class="path" name="path" value="{{@$path}}">
            <input type="file" style="display: none" name="file[]" accept="image/x-png,image/jpeg,image/gif" id="file" multiple>
            <span onclick="openDialog()">
                {{--<i class="fas fa-upload add-icon"></i>--}}
                <button type="button" class="btn btn-theme-color btn-lg waves-effect filemanager-btn">
                    Upload
                </button>
            </span>
        </form>
    </div>
    <div class="breadcrumbs">
        @php
            {{
            $tokens = explode('/', $path);
            $lastToken = array_pop($tokens);
            $path = [];
            }}
        @endphp
        @foreach($tokens as $k)
            @php
                {{ $path[] = $k; $current = implode('/', $path); }}
            @endphp
                <a href="/filemanager?path={{$current}}">
                    @if($path[0] == $current)
                        <span class="folderName">
                            <i class="fas fa-home"></i>
                        </span>
                    @else
                        <span class="folderName">
                        </span>
                    @endif
                </a>
                <span class="arrow">→</span>
                <span class="folderName">{!! $lastToken !!}</span>
        @endforeach
        <span class="folderName"></span>
    </div>
    <ul class="data">
        <ul class="data animated img-gallery">
            @foreach($final['folders'] as $k)
                @php
                    {{ $r = explode('/', $k);  }}
                @endphp
                <li class="folders" id="folder-li-{!! str_replace(array(" ",".","(",")"),"-",end($r)) !!}">
                    <span class="folders">
                        <span onclick="location.href = '/filemanager?path={{ $k }}';" class="icon folder full"></span>

                        <span class="name" :aria-valuemax="{{end($r)}}">{{end($r)}}</span>
                    </span>

                    <div id="hover-div-{!! str_replace(array(" ",".","(",")"),"-",end($r)) !!}" style="display: none">
                    <span class="delbtn" data-value="{{end($r)}}" data-name="folder">
                        <i class="fas fa-trash del-icon"></i>
                    </span>
                    </div>
                </li>
            @endforeach
            @foreach($final['files'] as $k)
                <?php
                    $checked = '';
                    $li_class = '';
                    $db_image = str_replace(array(" ",".","(",")"),"-",$k['name']);
                    if(!empty($image_name) && in_array($db_image, $image_name)){
                        $checked = 'checked';
                        $li_class = 'add-background selected';
                ?>
                <script>$("#insert-btn").show();</script>
                <?php
                    }
                ?>
                <li class="image-li {{ $li_class }}" id="li-{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}" onclick="show_border('{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}')">
                    <span class="image">
                        <img class="img-select" id="img-select" src="{{env('AWS_URL').$client_id.'/'. $k['name']  }}"
                             data-value="{{ $k['name']  }}" data-size="{{ $k['size'] }}" value="this.naturalHeight" style="width: 300px; height: 130px; object-fit: cover;">
                    </span>
                    <div id="outer-{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}" class="outer-div" style="display: none;">
                        <span class="inputGroup">
                            <input {{ $checked }} class="check-input" id="option-{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}" name="option{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}" onchange="show_border('{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}', 'box')" type="checkbox"/>
                            <label for="option-{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}"></label>
                        </span>
                        <span class="name" value="{{$k['name']}}">{{$k['name']}}</span>
                        <span class="image-size" value="{!! $k['size'] !!}">{!! $k['size'].' KB' !!}</span>
                        <span class="delbtn" data-value="{{$k['name']}}" data-name="file" onclick="show_border('{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}','del')">
                            <i class="fas fa-trash del-icon"></i>
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    </ul>
    <div class="nothingfound">
        <div class="nofiles"></div>
        <span>No files here.</span>
    </div>
</div>
<!-- The Modal -->
<div id="fileManageAddFolderModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <form action="/filemanager/addfolder" method="post" enctype="multipart/form-data" role="form">
            {{ csrf_field() }}
            <span class="close">x</span>
            <div class="modal-data">
                <p class="icon folder"></p>
                <br/>
                <input type="text" name="folder_name" placeholder="Enter Folder Name" value="" class="input" required autofocus>
                <br/>
                <input type="hidden" name="path" value="{{@$folder_path}}">
                <button type="submit" class="btn">ADD</button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>
<script>
    $(document).ready(function () {
        $('.delbtn').mousedown(function (event) {
            event.preventDefault();
            var type = $(this).data('name');
            if(type == 'file') {
                var r = confirm("Are you sure want to delete Image?");
            }else if(type == 'folder'){
                var r = confirm("Are you sure want to delete Folder?");
            }
            if(r == true) {
                var id = $(this).data('value');
                var path = $('.path').val();
                var token = $('input[name=_token]').val();
                $.blockUI({
                    css: {
                        border: 'none',
                        backgroundColor: 'transparent'
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '/delete_file',
                    data: 'name=' + id + '&_token=' + token + '&path=' + path + '&type=' + type,
                    success: function (response) {
                        console.log(response);
                        if (response = true) {
                            location.reload();
                        } else {
                            $('.messages').html('Folder not Deleted.');
                        }
                    },
                    complete: function () {
                        $.unblockUI();

                    }
                });
            }
        });
    });
</script>
<script>
    function openDialog() {
        document.getElementById("file").click();
    }

    document.getElementById("file").onchange = function () {
        document.getElementById("upload-form").submit();
    };
</script>
<script>
    // Get the modal
    var modal = document.getElementById('fileManageAddFolderModal');

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
        <?php foreach($final['files'] as $k){ ?>
        $("#li-{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}").mouseover(function(){
            $("#outer-{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}").show();
        });

        $("#li-{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}").mouseleave(function(){
            $("#outer-{!! str_replace(array(" ",".","(",")"),"-",$k['name']) !!}").hide();
        });
        <?php } ?>


        <?php foreach($final['folders'] as $k){
                $r = explode('/', $k);
            ?>
        $("#folder-li-{!! str_replace(array(" ",".","(",")"),"-",end($r)) !!}").mouseover(function(){
            $("#hover-div-{!! str_replace(array(" ",".","(",")"),"-",end($r)) !!}").show();
        });

        $("#folder-li-{!! str_replace(array(" ",".","(",")"),"-",end($r)) !!}").mouseleave(function(){
            $("#hover-div-{!! str_replace(array(" ",".","(",")"),"-",end($r)) !!}").hide();
        });
        <?php } ?>

        function show_border(checkName, actionFrom = null){
            if ($("#option-"+checkName).prop("checked") == true) {

                if(actionFrom == null) { // click on selected image box
                    @if(isset($events))
                        $(".check-input").prop("checked", false);
                        $('li.image-li').removeClass('add-background');
                        $('li.image-li').removeClass('selected');
                    @else
                        $("#option-" + checkName).prop("checked", false);
                        $('#li-' + checkName).removeClass('add-background');
                        $('#li-'+checkName).removeClass('selected');
                    @endif
                }else{ // click on unselected image checkbox
                    @if(isset($events))
                        $(".check-input").prop("checked", false);
                        $('li.image-li').removeClass('add-background');
                        $('li.image-li').removeClass('selected');
                        $("#option-" + checkName).prop("checked", true);
                        $('#li-'+checkName).addClass('add-background');
                        $('#li-'+checkName).addClass('selected');
                    @else
                        $('#li-'+checkName).addClass('add-background');
                        $('#li-'+checkName).addClass('selected');
                    @endif
                }
            }else{
                if(actionFrom == 'del') {
                    $("#option-" + checkName).prop("checked", false);
                    $('#li-' + checkName).removeClass('add-background');
                }else if(actionFrom == null){ // image box click when checkbox unchecked
                    @if(isset($events))
                        $(".check-input").prop("checked", false);
                        $('li.image-li').removeClass('add-background');
                        $('li.image-li').removeClass('selected');
                        $("#option-" + checkName).prop("checked", true);
                        $('#li-' + checkName).addClass('add-background');
                        $('#li-'+checkName).addClass('selected');
                    @else
                        $("#option-" + checkName).prop("checked", true);
                        $('#li-' + checkName).addClass('add-background');
                        $('#li-'+checkName).addClass('selected');
                    @endif
                }
                else{ // checkbox unchecked click
                    @if(isset($events))
                        $(".check-input").prop("checked", false);
                        $('li.image-li').removeClass('add-background');
                        $('li.image-li').removeClass('selected');
                    @else
                        $('#li-' + checkName).removeClass('add-background');
                        $('#li-' + checkName).removeClass('selected');
                    @endif
                }
            }

            var $insert_btn = $("#insert-btn").hide();
            $insert_btn.toggle( $("input[type='checkbox']").is(":checked") );
        }

</script>
<style>
    .image-li:hover .image{
        opacity: 0.35;
    }

    .image-size{
        color: #ffffff;
        font-size: 15px;
        font-weight: 700;
        position: absolute;
        left: 4px;
        bottom: 5px;
    }
    .delbtn{
        position: absolute;
        right: 4px;
        bottom: 5px;
    }

    .inputGroup {
        position: absolute;
        right: 4px;
        top: 5px;
    }
    .inputGroup label {
        padding: 12px 30px;
        display: inline-block;
        text-align: left;
        color: #3C454C;
        cursor: pointer;
        position: relative;
        z-index: 2;
        transition: color 200ms ease-in;
        overflow: hidden;

    }
    .inputGroup label:before {
         width: 10px;
         height: 10px;
         border-radius: 50%;
         content: '';
         position: absolute;
         left: 50%;
         top: 50%;
         transform: translate(-50%, -50%) scale3d(1, 1, 1);
         transition: all 300ms cubic-bezier(0.4, 0.0, 0.2, 1);
         opacity: 0;
         z-index: -1;
     }

    .inputGroup label:after {
         width: 15px;
         height: 15px;
         content: '';
         border: 2px solid #ccc;
         background-color: #ccc;
         background-image: url("data:image/svg+xml,%3Csvg width='32' height='32' viewBox='0 0 32 32' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5.414 11L4 12.414l5.414 5.414L20.828 6.414 19.414 5l-10 10z' fill='%23fff' fill-rule='nonzero'/%3E%3C/svg%3E ");
         background-repeat: no-repeat;
         background-position: -2px -3px;
         border-radius: 50%;
         z-index: 2;
         position: absolute;
         right: 0px;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
         transition: all 200ms ease-in;
     }

    .inputGroup input:checked ~ label {
        color: #fff;
    }
    .inputGroup input:checked ~ label:before {
         transform: translate(-50%, -50%) scale3d(56, 56, 1);
         opacity: 1;
     }
    .inputGroup input:checked ~ label:after {
         background-color: #2684FF;
         border-color: #2684FF;
     }

    .check-input {
        width: 32px;
        height: 32px;
        order: 1;
        z-index: 2;
        position: absolute;
        transform: translateY(-50%);
        cursor: pointer;
        visibility: hidden;
    }

    .outer-div{
        width: 300px;
        height: 134px;
        position: absolute;
        top: 0px;
    }

    .add-background{
         border: 2px solid #2684FF !important;
    }
</style>
</body>
</html>
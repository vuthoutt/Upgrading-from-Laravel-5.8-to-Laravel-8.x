{{-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> --}}
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head>
<body>


<!--    <h4>Seek if you can</h4>-->
<!--    <p>You won't be able to seek outside what was already played, even on mobile fullscreen mode</p>-->
    <video id="video" class="responsive" controls style="width: 100%; max-height: 600px;" controlsList="nodownload">
<!--        <source src="/prism/videos/SV_SITE_OP_BSL_V2.mp4" type="video/mp4">-->
        <!-- SV_FILM_02_V8.mp4 for Project manager -->
        <!-- SV_FILM_02_Project_Management_BSL_Final_1 -->
        <source src="{{asset('/videos/'.($file_name ?? ''))}}" type="video/mp4">
    </video>
    <div style="font-family: courrier, monospace; display: none">
        <p>currenttime: <span id="player-currenttime"></span><br>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;played: <span id="player-played"></span></p>

        <h5>Tested on</h5>
        <ul>
            <li>IE11</li>
            <li>Edge 16</li>
            <li>Chrome 66</li>
            <li>Safari iOS 11.3.1</li>
        </ul>

        <p>Code not optimized</p>
        <p><i>PS: It's of course not good UX to show a seek bar when you cannot seek. The controls/player design would have to be adapted. Unfortunately, on Mobile IOS (well AFAIK), the video player UI cannot be override when in fullscreen.</i></p>
    </div>

</body>
</html>

<script>

    // for videos
    var video = document.getElementById('video');
    //disable picture in picture mode
    video.disablePictureInPicture = true;
    var supposedCurrentTime = 0;
    var canSeek = false;
    // only complete for first time
    var count_complete = 0;
    // only start for first time
    var count_start = 0;

    video.addEventListener('timeupdate', function() {
        document.getElementById('player-currenttime').innerText = video.currentTime;
        document.getElementById('player-played').innerText = getPlayed();
        if (canSeek) {
            return
        }
        if (!video.seeking) {
            supposedCurrentTime = video.currentTime;
        }
        //check nearly complete not using ended event
        checkNearlyComplete(video.currentTime);
    });
    // prevent user from seeking
    video.addEventListener('seeking', function() {
        logPlayedRange();
        if (canSeek) {
            return
        }
        // accept rewind
        if (video.currentTime < supposedCurrentTime) {
            return
        }
        // accept seek to already played time
        if (isPlayed(video.currentTime)) {
            return
        }
        // guard agains infinite recursion:
        // user seeks, seeking is fired, currentTime is modified, seeking is fired, current time is modified, ...
        var delta = video.currentTime - supposedCurrentTime;
        if (Math.abs(delta) > 0.01) {
            console.log("Seeking is disabled");
            video.currentTime = supposedCurrentTime;
        }
    });

    function isPlayed(time) {
        var start = 0, end = 0;
        for (var i = 0; i < video.played.length; i++) {
            start = video.played.start(i);
            end = video.played.end(i);
            if (end - start < 1) {
                continue;
            }
            if (time >= start && time <= end) {
                return true;
            }
        }
        return false;
    }

    function checkNearlyComplete(time, nearly_second = 1) {
        // var start = 0, end = 0;
        var end = video.duration;
        if (time > 0 && time <= end && (end - time) <= nearly_second && count_complete == 0) {
            if ('{{ $type ?? ''}}' == 'project') {
                completeCourseSiteOperative();
            } else {
                completeCourseProject();
            }
            count_complete++;
            return true;
        }
        return false;
    }


    function logPlayedRange() {
        for (var i = 0; i < video.played.length; i++) {
            console.log(i, 'start:', video.played.start(i), 'end:', video.played.end(i));
        }
    }


    function getPlayed() {
        var played = 0;
        for (var i = 0; i < video.played.length; i++) {
            played += video.played.end(i) - video.played.start(i);
        }
        return played
    }
    setInterval(function () {
        // console.log(document.hasFocus());
        if(!document.hasFocus()){
            video.pause();
        }
    }, 1000);

    video.addEventListener('ended', function() {
        console.log("end");
        var playedWithOffset = getPlayed() + 10;

        if (playedWithOffset < video.duration) {
            return // end event occured from seeking to it.
            // Don't consider the video as "completed and let it auto seek back
        }
        console.log('can seek now');
        canSeek = true;
        supposedCurrentTime = 0; // reset state in order to allow for rewind
    });

    video.addEventListener('play', function() {
        if(count_start == 0){
            startCourse();
        }
        count_start ++;
        console.log('play');
    });

    video.addEventListener('complete', function() {
        console.log('complete');
    });

    video.addEventListener('pause', function() {
        console.log('pause');
    });

    function startCourse() {
        $.ajax
        ({
            type: "POST",
            url: "ajax_e_learning.php",
            data: {start_project_training: 1},
            dataType: "JSON",
            cache: false,
            success: function (result) {
                if(result){
                    if(result.error == true){
                        console.log('Something is wrong');
                    } else {
                        //does it need to disable begin button
                        // $('#e_begin').prop("disabled", false);
                    }
                }
            }
        });
    }

    function completeCourseProject() {
        $.ajax
        ({
            type: "GET",
            url: "{{route('ajax.project_training')}}",
            dataType: "JSON",
            cache: false,
            success: function (result) {
                if(result){
                    if(result.error == true){
                        console.log('Something is wrong');
                    } else {
                        //does it need to disable begin button
                        // $('#e_begin').prop("disabled", false);
                    }
                }
            }
        });
    }

     function completeCourseSiteOperative() {
        $.ajax
        ({
            type: "GET",
            url: "{{route('ajax.site_operative_training')}}",
            dataType: "JSON",
            cache: false,
            success: function (result) {
                if(result){
                    if(result.error == true){
                        console.log('Something is wrong');
                    } else {
                        //does it need to disable begin button
                        // $('#e_begin').prop("disabled", false);
                    }
                }
            }
        });
    }

</script>

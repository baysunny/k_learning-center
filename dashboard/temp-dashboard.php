<?php
session_start();

if($_SESSION["type"] > 3){
  echo "blank";
}else{

include $_SERVER['DOCUMENT_ROOT']."/includes/connection.inc.php";
include $_SERVER['DOCUMENT_ROOT']."/template/header.php";
include $_SERVER['DOCUMENT_ROOT']."/template/echo_html.php";

?>
    <link rel="stylesheet" href="/template_vendor/css/application.min.css">
    </head>
    <body class="layout layout-header-fixed layout-sidebar-fixed">
    <div class="layout-header">
        <?php nav_bar("Dashboard", ""); ?>
    </div>
    <div class="layout-main">
        <?php sidebar(); ?>

        <div class="layout-content">
            <div class="layout-content-body">
                <div class="row gutter-xs">
            <div class="col-md-6 col-lg-3 col-lg-push-0">
              <div class="card bg-primary">
                <div class="card-body">
                  <div class="media">
                    <div class="media-middle media-left">
                      <span class="bg-primary-inverse circle sq-48">
                        <span class="icon icon-works">&#80;</span>
                      </span>
                    </div>
                    <div class="media-middle media-body">
                      <h6 class="media-heading">Visitors</h6>
                      <h3 class="media-heading">
                        <span class="fw-l">1,031,760</span>
                      </h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-lg-push-3">
              <div class="card bg-info">
                <div class="card-body">
                  <div class="media">
                    <div class="media-middle media-left">
                      <span class="bg-info-inverse circle sq-48">
                        <span class="icon icon-works">&#97;</span>
                      </span>
                    </div>
                    <div class="media-middle media-body">
                      <h6 class="media-heading">Materi</h6>
                      <h3 class="media-heading">
                        <span class="fw-l">1,256 Items</span>
                      </h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-lg-pull-3">
              <div class="card bg-info">
                <div class="card-body">
                  <div class="media">
                    <div class="media-middle media-left">
                      <span class="bg-info-inverse circle sq-48">
                        <span class="icon icon-works">&#97;</span>
                      </span>
                    </div>
                    <div class="media-middle media-body">
                      <h6 class="media-heading">Materi</h6>
                      <h3 class="media-heading">
                        <span class="fw-l">40</span>
                      </h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3 col-lg-pull-0">
              <div class="card bg-warning">
                <div class="card-body">
                  <div class="media">
                    <div class="media-middle media-left">
                      <span class="bg-warning-inverse circle sq-48">
                        <span class="icon icon-envelope"></span>
                      </span>
                    </div>
                    <div class="media-middle media-body">
                      <h6 class="media-heading">Messages</h6>
                      <h3 class="media-heading">
                        <span class="fw-l">155,352.47</span>
                      </h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
                <div class="row gutter-xs">
                    <div class="col-xs-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="pull-left">
                                    <h4 class="card-title">Audience Overview</h4>
                                </div>
                                <div class="pull-right" data-toggle="buttons">
                                    <label class="btn btn-outline-primary btn-xs btn-pill active">
                                        <input type="radio" name="options" id="option1" autocomplete="off" checked="checked"> Past 24hr
                                    </label>
                                    <label class="btn btn-outline-primary btn-xs btn-pill">
                                        <input type="radio" name="options" id="option2" autocomplete="off"> Past 7 days
                                    </label>
                                    <label class="btn btn-outline-primary btn-xs btn-pill">
                                        <input type="radio" name="options" id="option3" autocomplete="off"> Past 30 days
                                    </label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-chart">
                                    <canvas data-chart="line" data-animation="false" data-labels='["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]' data-values='[{"label": "This week", "backgroundColor": "#f1595d", "borderColor": "#f1595d", "data": [5022, 11017, 12230, 8801, 14102, 21512, 9932]}, {"label": "Last week", "backgroundColor": "#13b6bc", "borderColor": "#13b6bc", "data": [5012, 7203, 10204, 15052, 14820, 21805, 13203]}]' data-tooltips='{"mode": "label"}' data-hide='["gridLinesX", "legend", "points"]' data-scales='{"yAxes": [{"gridLines": {"color": "#f5f5f5"}, "ticks": {"fontColor": "#bcc1c6", "maxTicksLimit": 5}}], "xAxes": [{ "gridLines": {"color": "#f5f5f5"}, "ticks": {"fontColor": "#bcc1c6"}} ]}' height="128"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">19,429 Signups</h4>
                            </div>
                            <div class="card-body">
                                <div class="card-chart">
                                    <canvas data-chart="bar" data-animation="false" data-labels='["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]' data-values='[{"label": "This week", "backgroundColor": "#f1595d", "borderColor": "#f1595d", "data": [23167, 15991, 13905, 17447, 24558, 22594, 23067]}, {"label": "Last week", "backgroundColor": "#13b6bc", "borderColor": "#13b6bc", "data": [7374, 16740, 22929, 16788, 12103, 16459, 24058]}]' data-tooltips='{"mode": "label"}' data-hide='["gridLinesX", "legend", "points"]' data-scales='{"yAxes": [{"gridLines": {"color": "#f5f5f5"}, "ticks": {"fontColor": "#bcc1c6", "maxTicksLimit": 5}}], "xAxes": [{ "gridLines": {"color": "#f5f5f5"}, "ticks": {"fontColor": "#bcc1c6"}} ]}' height="128"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gutter-xs">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-middle media-left">
                      <span class="bg-gray sq-64 circle">
                        <span class="icon-works">&#228;</span>
                      </span>
                                    </div>
                                    <div class="media-middle media-body">
                                        <h3 class="media-heading">
                                            <span class="fw-l">1,256 Issues</span>
                                            <span class="fw-b fz-sm text-danger">
                          <span class="icon icon-caret-up"></span>
                          15%
                        </span>
                                        </h3>
                                        <small>6 issues are unassigned</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-middle media-left">
                                        <div class="media-chart">
                                            <canvas data-chart="doughnut" data-animation="false" data-labels='["Resolved", "Unresolved"]' data-values='[{"backgroundColor": ["#f1595d", "#667589"], "data": [879, 377]}]' data-hide='["legend", "scalesX", "scalesY", "tooltips"]' height="64" width="64"></canvas>
                                        </div>
                                    </div>
                                    <div class="media-middle media-body">
                                        <h2 class="media-heading">
                                            <span class="fw-l">879</span>
                                            <small>Resolved</small>
                                        </h2>
                                        <small>More than 70% resolved issues</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="media">
                                    <div class="media-middle media-left">
                                        <div class="media-chart">
                                            <canvas data-chart="doughnut" data-animation="false" data-labels='["Resolved", "Unresolved"]' data-values='[{"backgroundColor": ["#667589", "#f1595d"], "data": [879, 377]}]' data-hide='["legend", "scalesX", "scalesY", "tooltips"]' height="64" width="64"></canvas>
                                        </div>
                                    </div>
                                    <div class="media-middle media-body">
                                        <h2 class="media-heading">
                                            <span class="fw-l">377</span>
                                            <small>Unresolved</small>
                                        </h2>
                                        <small>Less than 30% unresolved issues</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row gutter-xs">
                    <div class="col-md-8 col-md-push-4">
                        <div class="row gutter-xs">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-actions">
                                            <button type="button" class="card-action card-toggler" title="Collapse"></button>
                                            <button type="button" class="card-action card-reload" title="Reload"></button>
                                            <button type="button" class="card-action card-remove" title="Remove"></button>
                                        </div>
                                        <strong>Traffic Source</strong>
                                    </div>
                                    <div class="card-body" data-toggle="match-height">
                                        <ul class="list-group list-group-divided">
                                            <li class="list-group-item">
                                                <div class="media">
                                                    <div class="media-middle media-body">
                                                        <h6 class="media-heading">
                                                            <span>Direct</span>
                                                        </h6>
                                                        <h4 class="media-heading">67%
                                                            <small>124,029</small>
                                                        </h4>
                                                    </div>
                                                    <div class="media-middle media-right">
                                                      <span class="bg-primary circle sq-40">
                                                        <span class="icon-works">&#52;</span>
                                                      </span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="media">
                                                    <div class="media-middle media-body">
                                                        <h6 class="media-heading">
                                                            <span>Referrals</span>
                                                        </h6>
                                                        <h4 class="media-heading">21%
                                                            <small>38,875</small>
                                                        </h4>
                                                    </div>
                                                    <div class="media-middle media-right">
                              <span class="bg-primary circle sq-40">
                                <span class="icon-works">&#57;</span>
                              </span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="media">
                                                    <div class="media-middle media-body">
                                                        <h6 class="media-heading">
                                                            <span>Search Engines</span>
                                                        </h6>
                                                        <h4 class="media-heading">12%
                                                            <small>22,214</small>
                                                        </h4>
                                                    </div>
                                                    <div class="media-middle media-right">
                              <span class="bg-primary circle sq-40">
                                <span class="icon-works">&#75;</span>
                              </span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-heading">
                                            <strong>Location</strong>
                                            <span aria-hidden="true"> · </span>
                                            <a href="#">View full report</a>
                                        </div>
                                        <div class="card-map">
                                            <div data-toggle="vector-map" data-map="world_en" data-background-color="null" data-color="#ffffff" data-enable-zoom="true" data-hover-opacity="0.7" data-selected-color="#777" data-show-tooltip="true" data-scale-colors='["#13b6bc", "#00818a"]' data-values='{ "us":8167, "cn":6724, "gb":6527, "br":6330, "it":6232, "jp":6035, "ru":5871, "fr":5658, "in":5494, "au":5133, "ca":4379, "de":4034, "kp":4887, "ar":4608, "mx":4018, "tr":2706, "za":2066, "sa":1624, "id":1902, "gd":656, "lb":656, "cm":640, "cz":640, "ke":640, "mr":640, "om":640, "sk":640, "as":623, "be":623, "eg":623, "ma":623, "me":623, "my":623, "nz":623, "tv":623, "ua":623, "dz":607, "fj":607, "er":590, "fm":590, "ie":590, "ml":590, "pw":590, "se":590, "sl":590, "ug":590, "bs":574, "mk":574, "mt":574, "sv":574, "sy":574, "tn":574, "ba":558, "cg":558, "gs":558, "bf":541, "ci":541, "ge":541, "lv":541, "ph":541, "sz":541, "am":525, "bb":525, "iq":525, "af":508, "az":508, "ee":508, "ad":492, "bt":492, "by":492, "ch":492, "et":492, "gh":492, "gy":492, "io":492, "kn":492, "np":492, "so":492, "bi":476, "bz":476, "gm":476, "ki":476, "mw":476, "tg":476, "cd":459, "cl":459, "cv":459, "do":459, "la":459, "sb":459, "st":459, "ck":443, "pg":443, "rs":443, "tl":443, "na":426, "ve":426, "ae":410, "at":410, "kh":410, "lc":410, "lr":410, "sc":410, "tz":410, "uz":410, "bd":394, "bw":394, "gt":394, "jm":394, "pa":394, "pl":394, "tm":394, "tw":394, "fi":377, "ir":377, "ly":377, "sr":377, "ec":361, "ga":361, "mc":361, "mh":361, "mn":361, "bh":344, "gw":344, "sd":344, "sn":344, "to":344, "bn":328, "cr":328, "dm":328, "kw":328, "mg":328, "pe":328, "py":328, "th":328, "bo":312, "hn":312, "hu":312, "ng":312, "no":312, "pt":312, "al":295, "ao":295, "lt":295, "mm":295, "mu":295, "mv":295, "ne":295, "ni":295, "ss":295, "tt":295, "ws":295, "lu":279, "md":279, "si":279, "bg":262, "dk":262, "gn":262, "ht":262, "km":262, "vc":262, "vu":262, "zw":262, "cf":246, "cu":246, "cy":246, "gr":246, "nu":246, "rw":246, "sm":246, "tj":246, "vn":246, "ag":230, "bj":230, "pk":230, "ro":230, "ye":230, "co":213, "hr":213, "il":213, "kz":213, "qa":213, "gq":197, "jo":197, "mz":197, "sg":197, "td":197, "zm":197, "dj":180, "is":180, "kg":180, "lk":180, "nl":180, "nr":180, "uy":180, "es":164, "ls":164 }' style="height: 170px; width: 100%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-actions">
                                            <button type="button" class="card-action card-toggler" title="Collapse"></button>
                                            <button type="button" class="card-action card-reload" title="Reload"></button>
                                            <button type="button" class="card-action card-remove" title="Remove"></button>
                                        </div>
                                        <strong>Materi popular</strong>
                                    </div>
                                    <div class="card-body" data-toggle="match-height">
                                        <table class="table table-borderless table-middle">
                                            <tbody>
                                            <tr>
                                                <td class="col-xs-1">1.</td>
                                                <td class="col-xs-6">
                                                    <a class="link-muted" href="#">/getting-started</a>
                                                </td>
                                                <td class="col-xs-2">
                                                    <div class="text-right">185,118</div>
                                                </td>
                                                <td class="col-xs-3">
                                                    <div class="progress progress-sm m-y-0">
                                                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                            <span class="sr-only">100% Complete</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-xs-1">2.</td>
                                                <td class="col-xs-6">
                                                    <a class="link-muted" href="#">/pricing</a>
                                                </td>
                                                <td class="col-xs-2">
                                                    <div class="text-right">185,118</div>
                                                </td>
                                                <td class="col-xs-3">
                                                    <div class="progress progress-sm m-y-0">
                                                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                            <span class="sr-only">100% Complete</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-xs-1">3.</td>
                                                <td class="col-xs-6">
                                                    <a class="link-muted" href="#">/blog</a>
                                                </td>
                                                <td class="col-xs-2">
                                                    <div class="text-right">138,839</div>
                                                </td>
                                                <td class="col-xs-3">
                                                    <div class="progress progress-sm m-y-0">
                                                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
                                                            <span class="sr-only">75% Complete</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-xs-1">4.</td>
                                                <td class="col-xs-6">
                                                    <a class="link-muted" href="#">/support</a>
                                                </td>
                                                <td class="col-xs-2">
                                                    <div class="text-right">138,220</div>
                                                </td>
                                                <td class="col-xs-3">
                                                    <div class="progress progress-sm m-y-0">
                                                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">
                                                            <span class="sr-only">75% Complete</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-xs-1">5.</td>
                                                <td class="col-xs-6">
                                                    <a class="link-muted" href="#">/about-us</a>
                                                </td>
                                                <td class="col-xs-2">
                                                    <div class="text-right">17,385</div>
                                                </td>
                                                <td class="col-xs-3">
                                                    <div class="progress progress-sm m-y-0">
                                                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="15000" style="width: 50%">
                                                            <span class="sr-only">50% Complete</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-heading">
                                            <strong>Top Search Terms</strong>
                                            <span aria-hidden="true"> · </span>
                                            <a href="#">Refresh</a>
                                            <span aria-hidden="true"> · </span>
                                            <a href="#">Settings</a>
                                        </div>
                                        <div class="card-phrases">
                                            <div class="text-justify">
                                                <a class="link-muted" href="#" title="Search nodejs" style="font-size: 11px">nodejs</a>
                                                <a class="link-muted" href="#" title="Search modernizr" style="font-size: 9px">modernizr</a>
                                                <a class="link-muted" href="#" title="Search babel" style="font-size: 14px">babel</a>
                                                <a class="link-muted" href="#" title="Search elephant" style="font-size: 36px">elephant</a>
                                                <a class="link-muted" href="#" title="Search admin" style="font-size: 13px">admin</a>
                                                <a class="link-muted" href="#" title="Search package" style="font-size: 9px">package</a>
                                                <a class="link-muted" href="#" title="Search gruntjs" style="font-size: 12px">gruntjs</a>
                                                <a class="link-muted" href="#" title="Search underscore" style="font-size: 9px">underscore</a>
                                                <a class="link-muted" href="#" title="Search jquery" style="font-size: 20px">jquery</a>
                                                <a class="link-muted" href="#" title="Search material" style="font-size: 9px">material</a>
                                                <a class="link-muted" href="#" title="Search material-design" style="font-size: 11px">material-design</a>
                                                <a class="link-muted" href="#" title="Search validation" style="font-size: 11px">validation</a>
                                                <a class="link-muted" href="#" title="Search angularjs" style="font-size: 24px">angularjs</a>
                                                <a class="link-muted" href="#" title="Search react" style="font-size: 14px">react</a>
                                                <a class="link-muted" href="#" title="Search requirejs" style="font-size: 9px">requirejs</a>
                                                <a class="link-muted" href="#" title="Search form" style="font-size: 9px">form</a>
                                                <a class="link-muted" href="#" title="Search widgets" style="font-size: 14px">widgets</a>
                                                <a class="link-muted" href="#" title="Search sass" style="font-size: 23px">sass</a>
                                                <a class="link-muted" href="#" title="Search chartjs" style="font-size: 9px">chartjs</a>
                                                <a class="link-muted" href="#" title="Search theme" style="font-size: 14px">theme</a>
                                                <a class="link-muted" href="#" title="Search api" style="font-size: 33px">api</a>
                                                <a class="link-muted" href="#" title="Search gulp" style="font-size: 10px">gulp</a>
                                                <a class="link-muted" href="#" title="Search bootstrap" style="font-size: 23px">bootstrap</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-pull-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-actions">
                                    <button type="button" class="card-action card-toggler" title="Collapse"></button>
                                    <button type="button" class="card-action card-reload" title="Reload"></button>
                                    <button type="button" class="card-action card-remove" title="Remove"></button>
                                </div>
                                <strong>Messages</strong>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-segment">
                                            <span class="timeline-divider"></span>
                                        </div>
                                        <div class="timeline-content"></div>
                                    </div>
                                    <?php
                                    for($i=0; $i<5; $i++){
                                        echo '
                                    <div class="timeline-item">
                                        <div class="timeline-segment">
                                            <img class="timeline-media img-circle" width="40" height="40" src="/template_vendor/img/0299419341.jpg" alt="Harry Jones">
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-row">
                                                <a href="#" class="text-success">Harry Jones</a>
                                                <small>5 min ago</small>
                                                <button class="btn btn-link pull-right"><span class="text-success icon icon-mail-reply"></span></button>
                                            </div>
                                            <div class="timeline-row">
                                                A user accepted a request to receive a transferred repository.
                                            </div>
                                        </div>
                                    </div>';
                                    }

                                    ?>

                                </div>
                                <button class="btn btn-primary btn-sm btn-block" type="button">See all</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layout-footer">
            <div class="layout-footer-body">
                <small class="version">Version 1.4.0</small>
                <small class="copyright">2018 &copy; Elephant <a href="http://madebytilde.com/">Made by Tilde</a></small>
            </div>
        </div>
    </div>
    <div class="theme">
        <div class="theme-panel theme-panel-collapsed">
            <div class="theme-panel-controls">
                <button class="theme-panel-toggler" title="Expand theme panel ( ] )" type="button">
                    <span class="icon icon-cog icon-spin" aria-hidden="true"></span>
                </button>
            </div>
            <div class="theme-panel-body">
                <div class="custom-scrollbar">
                    <div class="custom-scrollbar-inner">
                        <h5 class="theme-heading">
                            <a href="/elephant/buy" class="btn btn-primary btn-block">Buy Elephant Now!</a>
                        </h5>
                        <ul class="theme-settings">
                            <li class="theme-settings-heading">
                                <div class="divider">
                                    <div class="divider-content">Theme Settings</div>
                                </div>
                            </li>
                            <li class="theme-settings-item">
                                <div class="theme-settings-label">Header fixed</div>
                                <div class="theme-settings-switch">
                                    <label class="switch switch-primary">
                                        <input class="switch-input" type="checkbox" name="layout-header-fixed" data-sync="true">
                                        <span class="switch-track"></span>
                                        <span class="switch-thumb"></span>
                                    </label>
                                </div>
                            </li>
                            <li class="theme-settings-item">
                                <div class="theme-settings-label">Sidebar fixed</div>
                                <div class="theme-settings-switch">
                                    <label class="switch switch-primary">
                                        <input class="switch-input" type="checkbox" name="layout-sidebar-fixed" data-sync="true">
                                        <span class="switch-track"></span>
                                        <span class="switch-thumb"></span>
                                    </label>
                                </div>
                            </li>
                            <li class="theme-settings-item">
                                <div class="theme-settings-label">Sidebar sticky*</div>
                                <div class="theme-settings-switch">
                                    <label class="switch switch-primary">
                                        <input class="switch-input" type="checkbox" name="layout-sidebar-sticky" data-sync="true">
                                        <span class="switch-track"></span>
                                        <span class="switch-thumb"></span>
                                    </label>
                                </div>
                            </li>
                            <li class="theme-settings-item">
                                <div class="theme-settings-label">Sidebar collapsed</div>
                                <div class="theme-settings-switch">
                                    <label class="switch switch-primary">
                                        <input class="switch-input" type="checkbox" name="layout-sidebar-collapsed" data-sync="false">
                                        <span class="switch-track"></span>
                                        <span class="switch-thumb"></span>
                                    </label>
                                </div>
                            </li>
                            <li class="theme-settings-item">
                                <div class="theme-settings-label">Footer fixed</div>
                                <div class="theme-settings-switch">
                                    <label class="switch switch-primary">
                                        <input class="switch-input" type="checkbox" name="layout-footer-fixed" data-sync="true">
                                        <span class="switch-track"></span>
                                        <span class="switch-thumb"></span>
                                    </label>
                                </div>
                            </li>
                            <li class="theme-settings-description">
                  <span>
                    <strong>Sidebar sticky*</strong> - by scrolling up and down the page, the menu placed on the sidebar moves along with the content until the bottom of the menu is reached. <a href="page-layouts.html">Learn more</a></span>
                            </li>
                        </ul>
                        <hr class="theme-divider">
                        <ul class="theme-variants">
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-1/" title="Theme 1">
                                    <img class="img-responsive" src="/template_vendor/img/f420a3cea0fb04862eb630f5a54b2733.jpg" alt="Theme 1">
                                </a>
                            </li>
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-2/" title="Theme 2">
                                    <img class="img-responsive" src="/template_vendor/img/3dd124286157b729cc38d9bd7045e384.jpg" alt="Theme 2">
                                </a>
                            </li>
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-3/" title="Theme 3">
                                    <img class="img-responsive" src="/template_vendor/img/35e0765272cd421a5352331003ae2ab1.jpg" alt="Theme 3">
                                </a>
                            </li>
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-4/" title="Theme 4">
                                    <img class="img-responsive" src="/template_vendor/img/4fcb4322807f1fd92aa3140adb37d4d9.jpg" alt="Theme 4">
                                </a>
                            </li>
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-5/" title="Theme 5">
                                    <img class="img-responsive" src="/template_vendor/img/b787e62313c23880e0797bfbbc3d150c.jpg" alt="Theme 5">
                                </a>
                            </li>
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-6/" title="Theme 6">
                                    <img class="img-responsive" src="/template_vendor/img/7489e404fb5088d63e5a5d55b27d546c.jpg" alt="Theme 6">
                                </a>
                            </li>
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-7/" title="Theme 7">
                                    <img class="img-responsive" src="/template_vendor/img/972b6c5882a45ee73d83a90a9852660c.jpg" alt="Theme 7">
                                </a>
                            </li>
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-8/" title="Theme 8">
                                    <img class="img-responsive" src="/template_vendor/img/0100e8b81ad03f81a64a0a5f49c5be41.jpg" alt="Theme 8">
                                </a>
                            </li>
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-9/" title="Theme 9">
                                    <img class="img-responsive" src="/template_vendor/img/69ece2eb60bdd2126e2acf27af43aa9b.jpg" alt="Theme 9">
                                </a>
                            </li>
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-10/" title="Theme 10">
                                    <img class="img-responsive" src="/template_vendor/img/2ef1cfdcf1da5256d7b994983bd8d457.jpg" alt="Theme 10">
                                </a>
                            </li>
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-11/" title="Theme 11">
                                    <img class="img-responsive" src="/template_vendor/img/cca2226fdaba079b321ba5cf05ba0adc.jpg" alt="Theme 11">
                                </a>
                            </li>
                            <li class="theme-variants-item">
                                <a class="theme-variants-link" href="/elephant-v1.4.0/theme-12/" title="Theme 12">
                                    <img class="img-responsive" src="/template_vendor/img/7a4ee939781f6165006cff1b5b8096d4.jpg" alt="Theme 12">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="theme-panel-footer">
                <a class="rounded sq-36 bg-facebook" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fdemo.madebytilde.com%2Felephant&t=The%20fastest%20way%20to%20build%20modern%20admin%20site%20for%20any%20platform%2C%20browser%2C%20or%20device" title="Share on Facebook" target="_blank">
                    <span class="icon icon-facebook"></span>
                </a>
                <a class="rounded sq-36 bg-twitter" href="https://twitter.com/intent/tweet?source=http%3A%2F%2Fdemo.madebytilde.com%2Felephant&text=The%20fastest%20way%20to%20build%20modern%20admin%20site%20for%20any%20platform%2C%20browser%2C%20or%20device:%20http%3A%2F%2Fdemo.madebytilde.com%2Felephant&via=madebytilde" title="Tweet" target="_blank">
                    <span class="icon icon-twitter"></span>
                </a>
                <a class="rounded sq-36 bg-googleplus" href="https://plus.google.com/share?url=http%3A%2F%2Fdemo.madebytilde.com%2Felephant" title="Share on Google+" target="_blank">
                    <span class="icon icon-google-plus"></span>
                </a>
                <a class="rounded sq-36 bg-linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=http%3A%2F%2Fdemo.madebytilde.com%2Felephant&title=The%20fastest%20way%20to%20build%20modern%20admin%20site%20for%20any%20platform%2C%20browser%2C%20or%20device&summary=Elephant%20is%20a%20front-end%20template%20created%20to%20help%20you%20build%20modern%20web%20applications%2C%20fast%20and%20in%20a%20professional%20manner.&source=http%3A%2F%2Fdemo.madebytilde.com%2Felephant" title="Share on LinkedIn" target="_blank">
                    <span class="icon icon-linkedin"></span>
                </a>
                <a class="rounded sq-36 bg-pinterest" href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fdemo.madebytilde.com%2Felephant&media=http://demo.madebytilde.com/elephant.jpg&description=Elephant%20is%20a%20front-end%20template%20created%20to%20help%20you%20build%20modern%20web%20applications%2C%20fast%20and%20in%20a%20professional%20manner." title="Pin it" target="_blank">
                    <span class="icon icon-pinterest-p"></span>
                </a>
                <a class="rounded sq-36 bg-default" href="mailto:?subject=The%20fastest%20way%20to%20build%20modern%20admin%20site%20for%20any%20platform%2C%20browser%2C%20or%20device&body=Elephant%20is%20a%20front-end%20template%20created%20to%20help%20you%20build%20modern%20web%20applications%2C%20fast%20and%20in%20a%20professional%20manner.:%20http%3A%2F%2Fdemo.madebytilde.com%2Felephant" title="Send email" target="_blank">
                    <span class="icon icon-envelope-o"></span>
                </a>
            </div>
        </div>
    </div>
    <script src="/template_vendor/js/vendor.min.js"></script>
    <script src="/template_vendor/js/elephant.min.js"></script>
    <script src="/template_vendor/js/application.min.js"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-83990101-1', 'auto');
        ga('send', 'pageview');
    </script>
    </body>

<?php
}
include $_SERVER['DOCUMENT_ROOT']."/template/footer.php";
?>

<!--ALL THIRD PART JAVASCRIPTS-->
<script src="/orion/public/vendor/js/vendor.footer.js?v={{ config('system.versioning') }}"></script>

<!--nextloop.core.js-->
<script src="/orion/public/js/core/ajax.js?v={{ config('system.versioning') }}"></script>

<!--MAIN JS - AT END-->
<script src="/orion/public/js/core/boot.js?v={{ config('system.versioning') }}"></script>

<!--EVENTS-->
<script src="/orion/public/js/core/events.js?v={{ config('system.versioning') }}"></script>

<!--CORE-->
<script src="/orion/public/js/core/app.js?v={{ config('system.versioning') }}"></script>

<!--BILLING-->
<script src="/orion/public/js/core/billing.js?v={{ config('system.versioning') }}"></script>

<!--GMAPS-->
<script src="/orion/public/js/core/gmap.js?v={{ config('system.versioning') }}"></script>

<!--project page charts-->
@if (@config('visibility.projects_d3_vendor'))
    <script src="/orion/public/vendor/js/d3/d3.min.js?v={{ config('system.versioning') }}"></script>
    <script src="/orion/public/vendor/js/c3-master/c3.min.js?v={{ config('system.versioning') }}"></script>
@endif

<!--stripe payments js-->
@if (@config('visibility.stripe_js'))
    <script src="https:js.stripe.com/v3/"></script>
@endif

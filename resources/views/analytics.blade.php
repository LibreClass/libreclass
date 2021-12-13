<script type="application/javascript" async src="https://www.googletagmanager.com/gtag/js?id={{ env('MIX_GA_TRACKING_ID') }}"></script>
<script type="application/javascript">
	window.dataLayer = window.dataLayer || [];
	function gtag() {
		dataLayer.push(arguments);
	}
	gtag('js', new Date());
	gtag('config', "{{ env('MIX_GA_TRACKING_ID') }}");
	$.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
</script>

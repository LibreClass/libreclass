<style>
.logo1{
  width: 90px;
}
.logo{
  width: 140px;
}

</style>
<header style="margin-bottom: 15px;">
	<table>
		<tr>
		<td style="padding-right: 40px">
				@if(!empty($institution->photo) && file_exists(public_path($institution->photo)))
					<img src="{{ public_path() . $institution->photo }}" class="logo1" style="margin-left:3px;">
				@endif
			</td>
			<td class="text-center">
				<div style="width:1100px;">
					<h4 >{{ $institution->name }}</h4>
					<h5>
						{{ collect([ $institution->street, $institution->local ])->filter()->implode(', ') }}
					</h5>
					<h5>Código UEE: {{ $institution->uee }}</h5>
				</div>
			</td>

			<td style="padding-left: 10px;">
				@if(optional($institution->city)->name == 'Arroio dos Ratos')
					<img
						
						src="{{ public_path('/images/logo-arroio_dos_ratos-rs.jpg') }}"
						class="logo" style="margin-right:5px;"
					>
				@else
					<img
						src="{{ public_path('/images/logo-libreclass-vertical.png') }}"
						class="logo" style="margin-right:5px;"
					>
				@endif
			</td>
		</tr>
	</table>
</header>



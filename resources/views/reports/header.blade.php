<header style="margin-bottom: 15px;">
	<table>
		<tr>
			<td style="width: 25%; padding-right: 15px;">
				@if(optional($institution->city)->name == 'Arroio dos Ratos')
					<img
						style="width: 85%;"
						src="{{ public_path('/images/logo-arroio_dos_ratos-rs.jpg') }}"
						class="img-responsive"
					>
				@else
					<img
						src="{{ public_path('/images/logo-libreclass-vertical.png') }}"
						class="img-responsive"
					>
				@endif
			</td>
			<td class="text-center">
				<div style="width: 100%;">
					<h4>{{ $institution->name }}</h4>
					<h5>
						{{ collect([ $institution->street, $institution->local ])->filter()->implode(', ') }}
					</h5>
					<h5>Código UEE: {{ $institution->uee }}</h5>
				</div>
			</td>
			<td style="width: 20%; padding-left: 15px;">
				@if(!empty($institution->photo) && file_exists(public_path($institution->photo)))
					<img src="{{ public_path() . $institution->photo }}" class="img-responsive">
				@endif
			</td>
		</tr>
	</table>
</header>
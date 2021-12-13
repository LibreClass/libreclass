	<div class="modal fade" id="modalPeriods" tabindex="-1" data-backdrop="static" role="Modal New Periods" aria-labelledby="modalPeriodsLabel" aria-hidden="true">
	    <div class="modal-dialog">

	        <div class="modal-content">

	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title text-blue" id="modalPeriodsLabel">
						<i class="fa fa-fw fa-bookmark"></i>
						<b>{{ ucfirst(strtolower(session('period.singular'))) }}</b>
					</h4>
	            </div>

	            <div class="modal-body">
	                <div class="form-group">
	                    <form action="/course/periods" id="add-periods" class="registerForm">
	                        <label>Curso</label>
	                        <select name="course" class="form-control" placeholder="Escolha um curso">
	                            @foreach($listcourses as $i => $course)
	                            <option value="{{ $i }}">{{ $course }}</option>
	                            @endforeach
	                        </select>
	                    </form>
	                </div>
	                <div id="list-periods" class="list-unstyled">
	                </div>

	                <form class="row input-edit-period">
	                    <div class="col-xs-4">
	                        <input
								type="text" 
							    id="input-new-period"
								name="period"
								class="form-control input-border-none"
								placeholder="Nov{{ strtolower(session('period.article')) . ' ' . strtolower(session('period.singular')) }}">
	                    </div>
	                    <div class="col-xs-2">
	                        <button class="btn btn-primary btn-sm">Salvar</button>
	                    </div>
	                </form>
	            </div>

	            <div class="modal-footer">
					<lc-button class="reload-page" variant="secondary" data-dismiss="modal"> Sair </lc-button>
	            </div>

	        </div>
	    </div>
	</div>
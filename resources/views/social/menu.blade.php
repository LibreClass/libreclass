@if (auth()->user()->type == "I")

  <a href="/courses" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="fa fa-folder-o"></i>
        </div>
        <div class="col-xs-10">
          <span class="hidden-xs">Cursos</span>
        </div>
      </div>
    </div>
  </a>

  <a href="/periods" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="fa fa-bookmark"></i>
        </div>
        <div class="col-xs-10">
          <span class="hidden-xs">{{ ucfirst(strtolower(session('period.plural'))) }}</span>
        </div>
      </div>
    </div>
  </a>

  <a href="/disciplines" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="fa fa-list-ul"></i>
        </div>
        <div class="col-xs-10">
          <span href='{{"/disciplines"}}' class="hidden-xs">Disciplinas</span>
        </div>
      </div>
    </div>
  </a>

  <a href="/classes?year={{date('Y')}}" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="icon-classes"></i>
        </div>
        <div class="col-xs-10">
          <span class="hidden-xs">Turmas</span>
        </div>
      </div>
    </div>
  </a>

  <a href="/user/teacher" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="icon-teacher"></i>
        </div>
        <div class="col-xs-10">
          <span class="hidden-xs">Professores</span>
        </div>
      </div>
    </div>
  </a>

  <a href="/user/student" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="fa fa-graduation-cap"></i>
        </div>
        <div class="col-xs-10">
          <span class="hidden-xs">Alunos</span>
        </div>
      </div>
    </div>
  </a>
{{--
  <a href="/permissions" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="fa fa-users"></i>
        </div>
        <div class="col-xs-10">
          <span class="hidden-xs">Instituição</span>
        </div>
      </div>
    </div>
  </a>

  <a href="/import" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="fa fa-upload"></i>
        </div>
        <div class="col-xs-10">
          <span class="hidden-xs">Importar dados</span>
        </div>
      </div>
    </div>
  </a>
--}}

 @elseif (auth()->user()->type == "P")

  <a href="/lectures" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="fa fa-files-o"></i>
        </div>
        <div class="col-xs-10">
          <span class="hidden-xs">Diários</span>
        </div>
      </div>


    </div>
  </a>

@elseif (auth()->user()->type == "S")

  <a href="/classrooms/campus" class="menu-option">
    <div class="sidebar-option">
      <span class="hidden-xs">Campus</span>
    </div>
  </a>

  <a href="/classrooms" class="menu-option">
    <div class="sidebar-option">
      <span class="hidden-xs">Prédios</span>
    </div>
  </a>

  <a href="/classrooms" class="menu-option">
    <div class="sidebar-option">
      <span class="hidden-xs">Blocos</span>
    </div>
  </a>

  <a href="/classrooms" class="menu-option">
    <div class="sidebar-option">
      <span class="hidden-xs">Salas</span>
    </div>
  </a>

  <a href="/classrooms" class="menu-option">
    <div class="sidebar-option">
      <span class="hidden-xs">Laboratórios</span>
    </div>
  </a>

 @endif

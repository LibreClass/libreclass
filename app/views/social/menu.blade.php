@if( $user->type == "I" )

  <a href="/courses" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="fa fa-folder-o"></i>
        </div>
        <div class="col-xs-10">
          <span class="hidden-xs">Meus cursos</span>
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
          <span class="hidden-xs">Meus períodos</span>
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
          <span href='{{"/disciplines"}}' class="hidden-xs">Minhas disciplinas</span>
        </div>
      </div>
    </div>
  </a>

  <a href="/classes" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="icon-classes"></i>
        </div>
        <div class="col-xs-10">
          <span class="hidden-xs">Minhas turmas</span>
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
          <span class="hidden-xs">Meus professores</span>
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
          <span class="hidden-xs">Meus alunos</span>
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
          <span class="hidden-xs">Minha instituição</span>
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

 @elseif ( $user->type == "P" )

  <a href="/lectures" class="menu-option">
    <div class="sidebar-option">
      <div class="row">
        <div class="col-xs-2">
          <i class="fa fa-files-o"></i>
        </div>
        <div class="col-xs-10">
          <span class="hidden-xs">Meus Diários</span>
        </div>
      </div>


    </div>
  </a>

@elseif ( $user->type == "S" )

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

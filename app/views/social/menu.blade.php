
@if( $user->type == "I" )

  <a href="/courses" class="menu-option">
    <div class="sidebar-option">
      <i class="fa fa-folder-o"></i>
      <span class="hidden-xs"> Meus Cursos</span>
    </div>
  </a>

  <a href="/disciplines" class="menu-option">
    <div class="sidebar-option">
      <i class="fa fa-list-ul"></i>
      <span href='{{"/disciplines"}}' class="hidden-xs"> Minhas Disciplinas</span>
    </div>
  </a>

  <a href="/classes" class="menu-option">
    <div class="sidebar-option">
      <i class="icon-classes"></i>
      <span class="hidden-xs"> Minhas turmas</span>
    </div>
  </a>

  <a href="/user/teacher" class="menu-option">
    <div class="sidebar-option">
      <i class="icon-teacher"></i>
      <span class="hidden-xs"> Meus Professores</span>
    </div>
  </a>
  <a href="/user/student" class="menu-option">
    <div class="sidebar-option">
      <i class="fa fa-users"></i>
      <span class="hidden-xs"> Meus Alunos</span>
    </div>
  </a>
  
  <a href="/import" class="menu-option">
    <div class="sidebar-option">
      <i class="fa fa-upload"></i>
      <span class="hidden-xs"> Importar Dados</span>
    </div>
  </a>

 @elseif ( $user->type == "P" )


 
  <a href="/lectures" class="menu-option">
    <div class="sidebar-option">
      <i class="fa fa-files-o"></i>
      <span class="hidden-xs"> Meus Diários</span>
    </div>
  </a>
  
 @endif


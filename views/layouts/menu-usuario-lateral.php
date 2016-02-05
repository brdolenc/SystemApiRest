<ul class="list-group bg-white">
  <a href="<?php echo $pathUrl; ?>usuario/configuracoes" class="list-group-item <?php if($site_action=="configuracoes" || $site_action=="configuracoes_editar") { echo "active"; } ?>">Configurações</a></li>
  <a href="<?php echo $pathUrl; ?>usuario/api" class="list-group-item <?php if($site_action=="api") { echo "active"; } ?>">Banco de dados</a></li>
  <a href="<?php echo $pathUrl; ?>usuario/dados" class="list-group-item <?php if($site_action=="dados") { echo "active"; } ?>">Meus dados</a></li>
  <a href="<?php echo $pathUrl; ?>usuario/documentacao" class="list-group-item <?php if($site_action=="documentacao") { echo "active"; } ?>">Documentação</a></li>
</ul>

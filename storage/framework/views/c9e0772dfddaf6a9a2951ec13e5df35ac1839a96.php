<?php $__env->startSection('titulo','Desempenho'); ?>

<?php $__env->startSection('corpo'); ?>
    <div class="row">
        <?php $__currentLoopData = $empresas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empresa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-header bg-primary text-center text-white border-primary">
                        <small class="fonte-weight-bold"><i class="fas fa-chart-pie"></i> <?php echo e($empresa->sigla); ?></small> - <?php echo e($empresa->descricao); ?>

                    </div>
                    <ul class="list-group list-group-flush login-card border-primary">
                        <li class="list-group-item">
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <canvas id="<?php echo e($empresa->id_empresa); ?>_grafico1"></canvas>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <canvas id="<?php echo e($empresa->id_empresa); ?>_grafico2"></canvas>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <canvas id="<?php echo e($empresa->id_empresa); ?>_grafico3"></canvas>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6">
                                    <canvas id="<?php echo e($empresa->id_empresa); ?>_grafico4"></canvas>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <canvas id="<?php echo e($empresa->id_empresa); ?>_grafico5"></canvas>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script_principal'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php $__currentLoopData = $empresas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empresa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <script>
            new Chart(document.getElementById("<?php echo e($empresa->id_empresa); ?>_grafico1"),<?php echo consulta_grafico($empresa->id_empresa, 1); ?>);
        </script>
        <script>
            new Chart(document.getElementById("<?php echo e($empresa->id_empresa); ?>_grafico2"),<?php echo consulta_grafico($empresa->id_empresa, 2); ?>);
        </script>
        <script>
            new Chart(document.getElementById("<?php echo e($empresa->id_empresa); ?>_grafico3"),<?php echo consulta_grafico($empresa->id_empresa, 3); ?>);
        </script>
        <script>
            new Chart(document.getElementById("<?php echo e($empresa->id_empresa); ?>_grafico4"),<?php echo consulta_grafico($empresa->id_empresa, 4); ?>);
        </script>
        <script>
            new Chart(document.getElementById("<?php echo e($empresa->id_empresa); ?>_grafico5"),<?php echo consulta_grafico($empresa->id_empresa, 5); ?>);
        </script>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.bpms', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/subdominio/developer/resources/views/graph/bi.blade.php ENDPATH**/ ?>
<div class="container">
    <h2 class="text-center">
        Nominees for Torch Bearers award
    </h2>
    <?php if ( ! empty($data) ): ?>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Nominee</th>
                    <th>Email/Website</th>
                    <th>Tel. no.</th>
                    <th>No. of nominations</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $d): ?>
                <tr>
                    <td><?= $d->nom_name ?></td>
                    <td><?= $d->em_web ?></td>
                    <td><?= $d->tel ?></td>
                    <td><?= $d->num ?></td>
                </tr>
            <?php endforeach; ?>								
            </tbody>
        </table>
    <?php endif; ?>
</div>
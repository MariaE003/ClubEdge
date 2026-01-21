<?php if (empty($users)): ?>
    <tr>
        <td colspan="5" class="text-muted" style="padding:16px;">Aucun résultat</td>
    </tr>
<?php else: ?>
    <?php foreach ($users as $u): ?>
        <tr>
            <td>
                <div class="flex items-center gap-3">
                    <div class="avatar avatar-sm bg-black text-white">
                        <?= strtoupper(substr($u->nom, 0, 1) . substr($u->prenom, 0, 1)) ?>
                    </div>
                    <span class="font-medium">
                        <?= htmlspecialchars($u->nom . ' ' . $u->prenom) ?>
                    </span>
                </div>
            </td>

            <td class="text-muted"><?= htmlspecialchars($u->email) ?></td>

            <td>
                <div class="flex items-center gap-2">
                    <div class="avatar avatar-xs">CI</div>
                    <span class="text-sm">Club Informatique</span>
                    <span class="badge badge-primary badge-sm"><?= htmlspecialchars($u->role) ?></span>
                </div>
            </td>

            <td class="text-muted">
                <?php
                $date = new DateTime($u->dateC ?? $u->dateC ?? 'now');
                echo $date->format('Y/m/d');
                ?>
            </td>

            <td>
                <div class="flex gap-1">
                    <a href="editUser/<?= $u->id ?>" class="btn btn-icon btn-ghost btn-sm" title="Modifier">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </a>
                    <button class="btn btn-icon btn-ghost btn-sm text-error" title="Supprimer">
                        <a href="deleteUser/<?= $u->id ?>" title="supprimer">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5">
                                <polyline points="3 6 5 6 21 6" />
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                            </svg>
                        </a>
                    </button>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
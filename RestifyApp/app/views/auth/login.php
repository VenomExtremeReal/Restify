<?php $title = 'Login - Restify'; ?>
<?php include '../layout/header.php'; ?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        Cadastro realizado com sucesso! Faça seu login.
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="card" style="max-width: 400px; margin: 2rem auto;">
    <h2>Login</h2>
    
    <form method="POST" data-validate>
        <div class="form-group">
            <label class="form-label">Tipo de Login:</label>
            <select name="type" class="form-control" required>
                <option value="restaurant">Restaurante</option>
                <option value="admin">Administrador</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label class="form-label">Senha:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
    
    <p style="text-align: center; margin-top: 1rem;">
        Não tem conta? <a href="<?= BASE_URL ?>/auth/register">Cadastre-se</a>
    </p>
    
    <div style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 5px;">
        <h4>Credenciais de Teste:</h4>
        <p><strong>Admin:</strong><br>
        Email: admin@restify.com<br>
        Senha: admin123</p>
    </div>
</div>

<?php include '../layout/footer.php'; ?>
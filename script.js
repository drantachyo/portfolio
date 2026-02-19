// ===== СИСТЕМА ЧАСТИЦ =====
const canvas = document.getElementById('particleCanvas');
if (canvas) {
    const ctx = canvas.getContext('2d');
    let particles = [];
    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;
    let mouseRadius = 80;

    // Настройка canvas
    function resizeCanvas() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // Класс частицы
    class Particle {
        constructor(x, y) {
            this.x = x;
            this.y = y;
            this.originalX = x;
            this.originalY = y;
            this.size = Math.random() * 2.5 + 1.5;
            this.vx = 0;
            this.vy = 0;
            this.damping = 0.92;
        }

        update(mouseX, mouseY) {
            // Расстояние до мышки
            const dx = this.x - mouseX;
            const dy = this.y - mouseY;
            const distance = Math.sqrt(dx * dx + dy * dy);

            // Эффект "продавливания" - частица отталкивается от мышки
            if (distance < mouseRadius) {
                const angle = Math.atan2(dy, dx);
                const force = (mouseRadius - distance) / mouseRadius;
                this.vx += Math.cos(angle) * force * 3;
                this.vy += Math.sin(angle) * force * 3;
            }

            // Притяжение к исходной позиции
            const returnDx = this.originalX - this.x;
            const returnDy = this.originalY - this.y;
            const returnDistance = Math.sqrt(returnDx * returnDx + returnDy * returnDy);
            
            if (returnDistance > 1) {
                const returnAngle = Math.atan2(returnDy, returnDx);
                this.vx += Math.cos(returnAngle) * 0.15;
                this.vy += Math.sin(returnAngle) * 0.15;
            }

            // Применяем скорость
            this.x += this.vx;
            this.y += this.vy;
            this.vx *= this.damping;
            this.vy *= this.damping;

            // Отскок от краёв
            if (this.x < 0 || this.x > canvas.width) {
                this.vx *= -0.5;
                this.x = Math.max(0, Math.min(canvas.width, this.x));
            }
            if (this.y < 0 || this.y > canvas.height) {
                this.vy *= -0.5;
                this.y = Math.max(0, Math.min(canvas.height, this.y));
            }
        }

        draw() {
            ctx.fillStyle = `rgba(16, 185, 129, 0.5)`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    // Отслеживание движения мышки
    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;

        // Обновляем переменные CSS для узора
        const x = (e.clientX / window.innerWidth) * 100;
        const y = (e.clientY / window.innerHeight) * 100;
        document.body.style.setProperty('--mouse-x', x + '%');
        document.body.style.setProperty('--mouse-y', y + '%');
    });

    // Анимационный цикл
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Обновляем и рисуем частицы
        for (let i = 0; i < particles.length; i++) {
            particles[i].update(mouseX, mouseY);
            particles[i].draw();
        }

        requestAnimationFrame(animate);
    }

    // Создаём начальные частицы в сетке
    const gridSize = 30;
    for (let x = 0; x < canvas.width; x += gridSize) {
        for (let y = 0; y < canvas.height; y += gridSize) {
            // Добавляем небольшую случайность
            const offsetX = (Math.random() - 0.5) * gridSize * 0.3;
            const offsetY = (Math.random() - 0.5) * gridSize * 0.3;
            particles.push(new Particle(x + offsetX, y + offsetY));
        }
    }

    animate();
}

// ===== ФОРМА =====
const orderForm = document.getElementById('orderForm');
if (orderForm) {
    orderForm.addEventListener('submit', function(event) {
        let name = document.getElementById('name').value;
        let email = document.getElementById('email').value;

        if (name === "" || email === "") {
            alert("Эй, заполни Имя и Email перед отправкой!");
            event.preventDefault();
        }
    });
}
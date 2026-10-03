import path from 'path'
import fs from 'fs'
import { glob } from 'glob'
import { src, dest, watch, series } from 'gulp'
import * as dartSass from 'sass'
import gulpSass from 'gulp-sass'
import sharp from 'sharp'
import plumber from 'gulp-plumber'
import webpack from 'webpack-stream'

const sass = gulpSass(dartSass)

const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js'
}

export function css( done ) {
    src(paths.scss, {sourcemaps: true})
        .pipe(plumber({
            errorHandler: function(err) {
                console.warn('⚠️  Error en SASS:', err.messageFormatted || err.message);
                this.emit('end');
            }
        }))
        .pipe( sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError) )
        .pipe( dest('./public/build/css', {sourcemaps: '.'}) );
    done()
}


function js() {
    return src('src/js/app.js') // Solo el archivo de entrada principal
        .pipe(plumber({
            errorHandler: function(err) {
                console.warn('⚠️  Error en JS:', err.message || err);
                this.emit('end');
            }
        }))
        .pipe(webpack({
            module: {
                rules: [
                    {
                        test: /\.css$/i,
                        use: ['style-loader', 'css-loader']
                    }
                ]
            },
            mode: 'production', // Usa 'production' para minificar automáticamente
            devtool: 'source-map',
            output: {
                filename: 'bundle.min.js'
            }
        }))
        .pipe(dest('./public/build/js'))
}



export async function imagenes(done) {
    const srcDir = './src/img';
    const buildDir = './public/build/img';
    // Solo captura archivos PNG, JPG, JPEG y GIF válidos
    const images = await glob('./src/img/**/*.{png,jpg,jpeg,gif}', { nodir: true });

    // Crear un array de promesas
    const promises = images.map(file => procesarImagenes(file, buildDir, srcDir));
    
    // Esperar a que todas las promesas se resuelvan
    try {
        await Promise.all(promises);
        done();
    } catch (error) {
        console.error('Error procesando imágenes:', error);
        done(error);
    }
}

function procesarImagenes(file, buildDir, srcDir) {
    return new Promise((resolve, reject) => {
        const relativePath = path.relative(srcDir, path.dirname(file));
        const outputSubDir = path.join(buildDir, relativePath);
        
        if (!fs.existsSync(outputSubDir)) {
            fs.mkdirSync(outputSubDir, { recursive: true });
        }
        
        const baseName = path.basename(file, path.extname(file));
        const extName = path.extname(file).toLowerCase();
        const options = { quality: 80 };

        const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
        const outputFileWebp = path.join(outputSubDir, `${baseName}.webp`);
        const outputFileAvif = path.join(outputSubDir, `${baseName}.avif`);

        // Ejecutar todas las conversiones en paralelo
        Promise.all([
            sharp(file).toFile(outputFile),
            sharp(file).webp(options).toFile(outputFileWebp),
            sharp(file).avif(options).toFile(outputFileAvif)
        ])
        .then(() => resolve())
        .catch(reject);
    });
}

export function dev() {
    watch( paths.scss, css );
    watch( paths.js, js );
    watch('src/img/**/*.{png,jpg,jpeg,gif}', imagenes);
}

export default series( js, css, imagenes, dev )
export const build = series(js, css, imagenes)
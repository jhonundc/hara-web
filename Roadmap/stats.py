# Roadmap/stats.py

from datetime import datetime

def main():
    print("🧼 Generando reporte de la tienda Hará Artesanal...")

    # Datos simulados de ventas (puedes cambiarlos luego)
    ventas = {
        "jabones": 100,
        "velas": 60,
    }

    total = sum(ventas.values())

    # Crear contenido del reporte
    contenido = [
        f"🕒 Reporte generado el {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n",
        f"Ventas totales: {total} unidades\n",
        "\nDetalle por producto:\n"
    ]

    for producto, cantidad in ventas.items():
        contenido.append(f"- {producto}: {cantidad} unidades\n")

    # Guardar el reporte en la raíz del repositorio
    with open("stats_report.txt", "w") as archivo:
        archivo.writelines(contenido)

    print("✅ Reporte guardado correctamente como 'stats_report.txt'.")

if __name__ == "__main__":
    main()

<template>
    <div class="relative w-full h-full" id="ports-demo">
        <div class="w-full h-[77vh] viewport">
            <screen ref="screen" style="border: none">
                <edge
                    v-for="edge in graph.edges"
                    :data="edge"
                    :nodes="graph.nodes"
                    :key="edge.id"
                ></edge>
                <node
                    :data="node"
                    ref="node"
                    class="overflow-hidden"
                    v-for="node in graph.nodes"
                    :key="node.id"
                >
                    <div class="node-header px-[6px]">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="absolute icon icon-tabler icon-tabler-x w-[14px] z-[2] cursor-pointer right-[3px] top-[3px] hover:scale-110 transition-all duration-200 stroke-[#F3798A] hover:stroke-[#ec2944]"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            @click="
                                form.nodoA = node;
                                remove();
                            "
                        >
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="grid">
                        <div style="border-bottom: none">
                            <div
                                v-for="input in node.inputs"
                                :key="node.id + ':' + input"
                            >
                                <port
                                    ref="port"
                                    class="w-full hover:bg-[#8EEAB0]"
                                    :id="node.id + ':' + input"
                                    :edgesTo="getInputEdges(node, input)"
                                >
                                    <div
                                        class="port-inner text-center px-[15px]"
                                        @mousedown.prevent.stop="
                                            (evt) =>
                                                startConnect(
                                                    node,
                                                    { input },
                                                    evt
                                                )
                                        "
                                        @mouseup.prevent.stop="
                                            createConnect(node, {
                                                input,
                                            })
                                        "
                                        :class="
                                            getInputEdges(node, input).length &&
                                            'connected'
                                        "
                                    >
                                        {{ node.Ceco }}
                                    </div>
                                </port>
                                {{ input.slice(1) }}
                            </div>
                        </div>
                        <div style="border-bottom: none">
                            <div
                                v-for="output in node.outputs"
                                :key="node.id + ':' + output"
                            >
                                {{ output.slice(5) }}
                                <port
                                    ref="port"
                                    :id="node.id + ':' + output"
                                    :edgesFrom="getOutputEdges(node, output)"
                                    class="w-full hover:bg-[#8EEAB0]"
                                >
                                    <div
                                        class="port-inner text-center px-[5px]"
                                        @mousedown.prevent.stop="
                                            (evt) =>
                                                startConnect(
                                                    node,
                                                    { output },
                                                    evt
                                                )
                                        "
                                        @mouseup.prevent.stop="
                                            createConnect(node, {
                                                output,
                                            })
                                        "
                                        :class="
                                            getOutputEdges(node, output)
                                                .length && 'connected'
                                        "
                                    >
                                        {{ node.Puesto }}
                                    </div>
                                </port>
                            </div>
                        </div>
                    </div>
                </node>
            </screen>
            <div class="flex items-center justify-evenly">
                <template v-for="son in sons" :key="idB">
                    <div
                        class="text-[20px] text-center cursor-pointer select-none relative px-[2rem] py-2"
                        @mouseover="form.area = son"
                        @mouseout="form.area = null"
                    >
                        <svg
                            v-if="son.ph != null"
                            xmlns="http://www.w3.org/2000/svg"
                            class="absolute icon icon-tabler icon-tabler-x w-[14px] z-[2] cursor-pointer right-[3px] top-[3px] hover:scale-110 transition-all duration-200 stroke-[#F3798A] hover:stroke-[#ec2944]"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="#FFFFFF"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            @click="
                                form.area = null;
                                form.jARid = son.ph;
                                removeAreaJefe();
                            "
                        >
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                        </svg>
                        <h2 class="text-[12px]">{{ son.padre }}</h2>
                        <h1>{{ son.nodoB }}</h1>
                        <h2 class="text-[12px]">{{ son.hijo }}</h2>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import { Screen, Node, Edge, graph, Port } from "vnodes";
import { useForm } from "@inertiajs/inertia-vue3";
import Dragable from "vuedraggable";

export default {
    components: {
        Screen,
        Node,
        Edge,
        Port,
        Dragable,
    },
    setup() {
        const form = useForm({
            nodoA: "",
            nodoB: "",
            nodoC: "",
            nodoD: "",
            jARid: null,
            area: null,
        });
        const gerencia = "";
        return {
            form,
            gerencia,
        };
    },
    data() {
        return {
            graph: new graph(),
            connecting: null, // { node: {}, input: str, output: str }
            mousePrev: { x: 0, y: 0 },
            zoom: 1,
        };
    },
    emits: ["area"],
    props: ["rels", "nodos", "area", "sons"],
    methods: {
        startConnect(node, { input, output }, evt) {
            if (this.connecting) return;
            const port = this.$refs.port.find(
                (p) => p.id === `${node.id}:${input || output}`
            );
            const edge = input && this.getInputEdges(node, input).reverse()[0];
            if (edge) {
                // edit exiting edge
                edge.active = true;
                this.connecting = {
                    node: this.graph.nodes.find((n) =>
                        input ? edge.from === n.id : edge.to === n.id
                    ),
                    input: output,
                    output: input,
                };
                this.form.nodoC = edge;
                this.form.nodoD = node;
            } else {
                // new edge
                this.graph.createEdge({
                    from: node.id,
                    to: node.id,
                    fromPort: input || output,
                    toPort: input || output,
                    fromAnchor: { ...port.offset },
                    toAnchor: { ...port.offset },
                    active: true,
                    type: "smooth",
                });
                this.connecting = {
                    node,
                    input,
                    output,
                };

                this.form.nodoA = node;
            }
            this.mousePrev = { x: evt.clientX, y: evt.clientY };
            this.zoom = this.$refs.screen.panzoom.getZoom();
        },
        createConnect(node, { input, output }) {
            if (!this.connecting) return;
            if (
                this.isValidConnection({ node, input, output }, this.connecting)
            ) {
                if (input) {
                    this.activeEdge.to = node.id;
                    this.activeEdge.toPort = input;
                    this.form.nodoB = node;
                } else if (output) {
                    this.activeEdge.from = node.id;
                    this.activeEdge.fromPort = output;
                    this.form.nodoB = this.form.nodoA;
                    this.form.nodoA = node;
                }
                this.stopConnect();
                this.submit();
            } else {
                this.cancelConnect();
            }
        },
        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(route("organigrama.relacion"), {});
        },
        areaJefe() {
            console.log("entra");
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(route("organigrama.jefearea"), {
                    onFinish: () => {
                        this.form.reset();
                        this.modal();
                    },
                });
        },
        delete() {
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(route("organigrama.destroy"), {});
        },
        remove() {
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(route("organigrama.remove"), {
                    onFinish: () => {
                        this.form.reset();
                        this.modal();
                    },
                });
        },
        removeAreaJefe() {
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(route("organigrama.jefeAreaR"), {
                    onFinish: () => {
                        this.form.reset();
                        this.modal();
                    },
                });
        },
        cancelConnect() {
            if (!this.connecting) return;
            this.graph.removeEdge(this.activeEdge);
            this.stopConnect();
            this.delete();
            if (this.form.area != null) this.areaJefe();
        },
        stopConnect() {
            if (this.activeEdge) {
                this.activeEdge.active = false;
            }
            this.$nextTick(() => {
                this.connecting = null;
            });

            this.$nextTick(() => {
                this.graph.graphNodes({
                    spacing: 25,
                    type: "tree",
                    dir: "down",
                });
                this.$refs.screen.zoomNodes(this.graph.nodes);
            });
        },
        isValidConnection(conA, conB) {
            return (
                conA &&
                conB &&
                conA.node &&
                conB.node &&
                conA.node !== conB.node &&
                ((conA.input && conB.output) || (conB.input && conA.output))
            );
        },
        // edges that go to this input
        getInputEdges(node, input) {
            return this.graph.edges.filter(
                (e) => e.to === node.id && e.toPort === input
            );
        },
        // edges that start from this output
        getOutputEdges(node, output) {
            return this.graph.edges.filter(
                (e) => e.from === node.id && e.fromPort === output
            );
        },
        onmousemove(e) {
            if (this.connecting) {
                const offset = {
                    x: (e.clientX - this.mousePrev.x) / this.zoom,
                    y: (e.clientY - this.mousePrev.y) / this.zoom,
                };
                const anchor = this.connecting.input
                    ? this.activeEdge.fromAnchor
                    : this.activeEdge.toAnchor;
                anchor.x += offset.x;
                anchor.y += offset.y;
                this.mousePrev = { x: e.clientX, y: e.clientY };
            }
        },
        modal() {
            let a = this.$props.area;
            this.$emit("area", { a });
        },
    },
    computed: {
        activeEdge: (vm) => vm.graph.edges.find((e) => e.active),
    },
    mounted() {
        window.ports = this; // DELETEME
        const node = this.nodos;
        node.forEach((nodo) => {
            this.graph.createNode({
                id: nodo.Puesto + "/" + nodo.Ceco,
                Nodeid: nodo.id,
                Ceco: nodo.Ceco,
                Puesto: nodo.Puesto,
                inputs: ["i"],
                outputs: ["o"],
            });
        });
        if (this.rels) {
            this.rels.forEach((rel) => {
                this.graph.createEdge({
                    from: rel.nodoA,
                    to: rel.nodoB,
                    fromPort: "o",
                    toPort: "i",
                    active: false,
                    type: "hsmooth",
                });
            });
        }
        this.$nextTick(() => {
            this.graph.graphNodes({ spacing: 25, type: "tree", dir: "down" });
            this.$refs.screen.zoomNodes(this.graph.nodes);
        });
        document.addEventListener("mouseup", this.cancelConnect);
        document.addEventListener("mousemove", this.onmousemove);
    },
    beforeUpdate() {
        window.ports = this; // DELETEME
        const node = this.nodos;
        this.graph.reset();
        this.$nextTick(() => {
            node.forEach((nodo) => {
                this.graph.createNode({
                    id: nodo.Puesto + "/" + nodo.Ceco,
                    Nodeid: nodo.id,
                    Ceco: nodo.Ceco,
                    Puesto: nodo.Puesto,
                    inputs: ["i"],
                    outputs: ["o"],
                });
            });
            if (this.rels) {
                this.rels.forEach((rel) => {
                    this.graph.createEdge({
                        from: rel.nodoA,
                        to: rel.nodoB,
                        fromPort: "o",
                        toPort: "i",
                        active: false,
                        type: "hsmooth",
                    });
                });
            }
            this.$nextTick(() => {
                this.graph.graphNodes({
                    spacing: 25,
                    type: "tree",
                    dir: "down",
                });
                this.$refs.screen.zoomNodes(this.graph.nodes);
            });
            document.addEventListener("mouseup", this.cancelConnect);
            document.addEventListener("mousemove", this.onmousemove);
        });
    },
    beforeDestroy() {
        document.removeEventListener("mouseup", this.cancelConnect);
        document.removeEventListener("mousemove", this.onmousemove);
    },
};
</script>

<style scoped>
.port-inner {
    background-color: #abc00000;
    display: inline-block;
    cursor: pointer;
    width: 100%;
}
</style>

<style>
#ports-demo .node .content {
    background-color: #eee;
    box-shadow: 2px 2px 2px 2px rgb(100, 100, 100, 0.2);
    /* outline: 2px solid #555; */
}
#ports-demo .edge {
    stroke: rgb(117, 117, 117);
    stroke-linejoin: round;
    marker-start: none;
    marker-end: none;
    stroke-dasharray: 5px 10px;
    stroke-dashoffset: 1000;
    stroke-linecap: round;
    animation: dash 20s linear infinite;
}
</style>

<style>
@keyframes dash {
    to {
        stroke-dashoffset: 0;
    }
}
</style>
